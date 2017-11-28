<?php

namespace GooglePhotosApi\Model;

use SimpleXMLElement;

/**
 * The object model of a GooglePhoto album
 */
class Album extends AbstractMedia
{
    /**
     * @var string
     */
	protected $albumTitle;

    /**
     * @var string
     */
	protected $photoUrl;

    /**
     * @var string
     */
	protected $thumbUrl;

    /**
     * @var int
     */
	protected $albumId;

    /**
     * @var int
     */
	protected $albumNumPhotos;

    /**
     * @var \DateTime
     */
	protected $publishedDate;

    /**
     * @var \DateTime
     */
	protected $updatedDate;

	/**
	 * @inheritDoc AbstractMedia
	 */
	public static function makeFromXml(SimpleXMLElement $entry,  $settings)
	{
		$album = new Album();

		$namespaces = $entry->getNameSpaces(true);
		$albumElement = $entry->children($namespaces['gphoto']);
		$media = $entry->children($namespaces['media']);
		$thumbnailAttr = $media->group->thumbnail->attributes();
		$photoAttr = $media->group->content->attributes();
		$album->setAlbumTitle((string) $entry->title);
        $album->setPublishedDate(new \DateTime((string)$entry->published));
        $album->setUpdatedDate(new \DateTime((string)$entry->updated));
		$album->setPhotoUrl((string) $photoAttr['url']);
		$album->setThumbUrl((string) $thumbnailAttr['url']);
		$album->setAlbumId((int) $albumElement->id);
		$album->setAlbumNumPhotos((int) $albumElement->numphotos);


		$album->setThumbUrl(self::resizeImage(
			$album->getThumbUrl(),
            $settings['thumb_size'],
            $settings['crop_mode'],
            $settings['should_crop']
		));

		return $album;
	}

    /**
     * Check if the album belongs to the list of excluded albums.
     *
     * @param $settings
     * @return bool
     */
	public function isExcluded($settings)
	{
		$albumsToIgnore = $settings['ignored_albums'];

		return (
			in_array($this->getAlbumId(), $albumsToIgnore) ||
			in_array($this->getAlbumTitle(), $albumsToIgnore)
		);
	}

    /**
     * @return mixed
     */
    public function getAlbumTitle()
    {
        return $this->albumTitle;
    }

    /**
     * @param mixed $albumTitle
     */
    public function setAlbumTitle($albumTitle)
    {
        $this->albumTitle = $albumTitle;
    }

    /**
     * @return mixed
     */
    public function getPhotoUrl()
    {
        return $this->photoUrl;
    }

    /**
     * @param mixed $photoUrl
     */
    public function setPhotoUrl($photoUrl)
    {
        $this->photoUrl = $photoUrl;
    }

    /**
     * @return mixed
     */
    public function getThumbUrl()
    {
        return $this->thumbUrl;
    }

    /**
     * @param mixed $thumbUrl
     */
    public function setThumbUrl($thumbUrl)
    {
        $this->thumbUrl = $thumbUrl;
    }

    /**
     * @return int
     */
    public function getAlbumId()
    {
        return $this->albumId;
    }

    /**
     * @param int $albumId
     */
    public function setAlbumId($albumId)
    {
        $this->albumId = $albumId;
    }

    /**
     * @return mixed
     */
    public function getAlbumNumPhotos()
    {
        return $this->albumNumPhotos;
    }

    /**
     * @param mixed $albumNumPhotos
     */
    public function setAlbumNumPhotos($albumNumPhotos)
    {
        $this->albumNumPhotos = $albumNumPhotos;
    }

    /**
     * @return mixed
     */
    public function getPublishedDate()
    {
        return $this->publishedDate;
    }

    /**
     * @param mixed $publishedDate
     */
    public function setPublishedDate($publishedDate)
    {
        $this->publishedDate = $publishedDate;
    }

    /**
     * @return mixed
     */
    public function getUpdatedDate()
    {
        return $this->updatedDate;
    }

    /**
     * @param mixed $updatedDate
     */
    public function setUpdatedDate($updatedDate)
    {
        $this->updatedDate = $updatedDate;
    }

}
