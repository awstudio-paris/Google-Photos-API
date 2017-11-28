<?php

namespace GooglePhotosApi\Client;

use Exception;
use GooglePhotosApi\Model\AbstractMedia;
use GooglePhotosApi\Model\Album;
use GooglePhotosApi\Model\Photo;
use GooglePhotosApi\Model\Video;
use GuzzleHttp\Client;
use stdClass;
use SimpleXMLElement;

/**
 * Picasa Access and Google Photos interface class.
 * Using Picasa Web Data API v3.0
 *
 * @see https://developers.google.com/identity/protocols/OAuth2WebServer#callinganapi
 * @see https://developers.google.com/picasa-web/docs/3.0/developers_guide_protocol
 * @see https://developers.google.com/picasa-web/docs/3.0/reference
 */
class GooglePhotosClient
{
    /**
     * @var array
     */
    protected $settings;

    /**
     * @var string
     */
    protected $googleAccessToken;

    /**
     * @var Client
     */
    protected $client;

    /**
     * PicasaClient constructor.
 * @param $settings array of settings for more info see setSettings
     * @see GooglePhotosClient::setSettings()
     */
    public function __construct($settings = null)
    {
        $this->setSettings($settings);
        $this->client = new Client();
    }


    /**
     * Get the list of all albums on an account
     *
     * @return Album[]
     * @throws Exception
     */
    public function getAlbumsList()
    {
        $url = "https://picasaweb.google.com/data/feed/api/user/default";

        $query = [
            'kind' => $this->settings['kind'],
            'thumbsize' => $this->settings['thumb_size'],
            'access' => $this->settings['visibility'],
        ];

        $query = $this->addPagination($query);

        $response = $this->client->request(
            'GET',
            $url,
            [
                'query' => $query,
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->googleAccessToken,
                    'GData-Version' => '3',
                    'Referer' => ''
                ],
            ]
        );


        if ($response->getStatusCode() != 200)
            throw new Exception("Error trying to query Picasa API. Response status: " . $response->getStatusCode());

        $albums = [];
        $xmlResult = $this->decodeXml($response->getBody());
        foreach ($xmlResult->entry as $entry) {
            $album = Album::makeFromXml($entry, $this->settings);

            if (!$album->isExcluded($this->settings))
                $albums[] = $album;
        }

        return $albums;
    }

    /**
     * Get the images of an album by id
     *
     * @param string $albumId A picasa album id
     * @param string $albumTitle A reference that will be assigned as the title of the album fetched
     *
     * @return array
     * @throws Exception
     */
    public function getAlbumImages($albumId, &$albumTitle = "")
    {
        $url = "https://picasaweb.google.com/data/feed/api/user/default/albumid/" . $albumId;

        $query = [
            'thumbsize' => $this->settings['thumb_size'],
            'access' => $this->settings['visibility'],
            'imgmax' => 'd' // Retrieve original image (full size)
        ];
        $query = $this->addPagination($query);

        $response = $this->client->request(
            'GET',
            $url,
            [
                'query' => $query,
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->googleAccessToken,
                    'GData-Version' => '3',
                    'Referer' => ''
                ],
            ]
        );


        if ($response->getStatusCode() != 200)
            throw new Exception("Error trying to query Picasa API. Response status: " . $response->getStatusCode());

        $images = [];
        $xmlResult = $this->decodeXml($response->getBody());
        $albumTitle = (string)$xmlResult->title[0];
        foreach ($xmlResult->entry as $entry) {
            if (AbstractMedia::isVideo($entry)) {
                $images[] = Video::makeFromXml($entry, $this->settings);
            } else {
                $images[] = Photo::makeFromXml($entry, $this->settings);
            }
        }

        return $images;
    }

    /**
     * Add pagination controls as query string to an url based on GData pagination format.
     *
     * @see https://developers.google.com/gdata/docs/2.0/reference#max-results
     * @see https://developers.google.com/gdata/docs/2.0/reference#start-index
     *
     * @param array $query
     * @return array $query
     */
    private function addPagination($query)
    {
        if (isset($this->settings['start_index']))
        {
            $query['start_index'] = $this->settings['start_index'];
        }
        if (isset($this->settings['max_results']))
        {
            $query['max_results'] = $this->settings['max_results'];
        }

        return $query;
    }

    /**
     * Decode an xml string with error handling
     *
     * @param string $response The XML string received by the API
     *
     * @return SimpleXMLElement|stdClass
     */
    private function decodeXml($response)
    {
        libxml_use_internal_errors(true);
        try {
            $xml = new SimpleXMLElement($response);
        } catch (Exception $e) {
            $error_message = 'SimpleXMLElement threw an exception.';
            foreach (libxml_get_errors() as $error_line) {
                $error_message .= "\t" . $error_line->message;
            }
            trigger_error($error_message);
            return new stdClass();
        }
        return $xml;
    }

    /**
     * @param $settings array of the client settings. Value key like it follows.
     *  All are optional. See api reference.
     *
     *    field             => type (possible_values) : default_value
     *
     *   'kind'             => string (album | photo | comment | tag | user) : album
     *   'visibility'       => string (all | private | public | visible) : all
     *   'thumb_size'       => int : 200
     *   'crop_mode'        => string (h, w, s) : 's'
     *   'should_crop'      => bool : false
     *   'max_results'      => int : null
     *   'start_index'      => int : null
     *   'ignored_albums'   => array : [] //albums that wants to be ignore, by title or ID
     *
     * @see https://developers.google.com/picasa-web/docs/2.0/reference
     */
    public function setSettings($settings)
    {
        if($settings === null){
            $this->settings = $this->getDefaultSettings();
        }else{
            $this->settings = array_merge($this->getDefaultSettings(), $settings);
        }
    }

    /**
     * @return array
     */
    private function getDefaultSettings(){
        if(isset($this->settings)){
            return $this->settings;
        }
        return [
            'visibility' => 'all',
            'should_crop' => 'false',
            'thumb_size' => 200,
            'crop_mode' => 's',
            'ignored_albums' => [],
            'kind' => 'album',
        ];
    }

    /**
     * @return string
     */
    public function getGoogleAccessToken()
    {
        return $this->googleAccessToken;
    }

    /**
     * @param string $googleAccessToken
     */
    public function setGoogleAccessToken(string $googleAccessToken)
    {
        $this->googleAccessToken = $googleAccessToken;
    }
}
