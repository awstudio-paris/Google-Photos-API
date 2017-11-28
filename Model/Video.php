<?php

namespace GooglePhotosApi\Model;

use SimpleXMLElement;

/**
 * The object model of a GooglePhoto video
 */
class Video extends AbstractMedia
{
    /**
     * @var string
     */
	protected $photoTitle;

    /**
     * @var string
     */
    protected $photoUrl;

    /**
     * @var string
     */
    protected $thumbUrl;

    /**
     * @var string
     */
    protected $photoSummary;

    /**
     * @var string
     */
    protected $photoPublished;

    /**
     * @var \DateTime
     */
    protected $photoUpdated;

    /**
     * @var int
     */
    protected $imageHeight;

    /**
     * @var int
     */
    protected $imageWidth;

    /**
     * @var ContentElement[]
     */
    protected $streams;

	/**
	 * @inheritDoc AbstractMedia
	 */
	public static function makeFromXml(SimpleXMLElement $entry, $settings)
	{
		$video = new Video();

		$namespaces = $entry->getNameSpaces(true);
		$gPhoto = $entry->children($namespaces['gphoto']);
		$media = $entry->children($namespaces['media']);
		$thumbnailAttr = $media->group->thumbnail->attributes();
        $streams = [];
		foreach ($media->group->content as $stream)
		{
			if (ContentElement::isVideo($stream)){
                $streams[] = ContentElement::makeFromXml($stream, $settings);
            }
		}
        $video->setStreams($streams);
		$video->setPhotoTitle((string) $entry->title[0]);
		$video->setPhotoUrl(self::getBestStream($video->streams)->getUrl());
		$video->setThumbUrl($thumbnailAttr['url']);
		$video->setPhotoSummary((string) $entry->summary[0]);
		$video->setPhotoPublished((string) $entry->published);
		$video->setPhotoUpdated((string) $entry->updated);
		$video->setImageHeight((int) $gPhoto->height);
		$video->setImageWidth((int) $gPhoto->width);

        $video->setThumbUrl(self::resizeImage(
            $video->getThumbUrl(),
            $settings['thumb_size'],
            $settings['crop_mode'],
            $settings['should_crop']
        ));

		return $video;
	}

	/**
	 * Get the url of the video stream with the highest resolution.
	 *
	 * @param ContentElement[] $streams
	 * @return ContentElement
	 */
	private static function getBestStream($streams)
	{
		$greatestStream = new ContentElement();

		foreach ($streams as $stream)
		{
			if ($stream->isBiggerThan($greatestStream))
			{
				$greatestStream = $stream;
			}
		}

		return $greatestStream;
	}

    /**
     * @return mixed
     */
    public function getPhotoTitle()
    {
        return $this->photoTitle;
    }

    /**
     * @param mixed $photoTitle
     */
    public function setPhotoTitle($photoTitle)
    {
        $this->photoTitle = $photoTitle;
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
     * @return mixed
     */
    public function getPhotoSummary()
    {
        return $this->photoSummary;
    }

    /**
     * @param mixed $photoSummary
     */
    public function setPhotoSummary($photoSummary)
    {
        $this->photoSummary = $photoSummary;
    }

    /**
     * @return mixed
     */
    public function getPhotoPublished()
    {
        return $this->photoPublished;
    }

    /**
     * @param mixed $photoPublished
     */
    public function setPhotoPublished($photoPublished)
    {
        $this->photoPublished = $photoPublished;
    }

    /**
     * @return mixed
     */
    public function getPhotoUpdated()
    {
        return $this->photoUpdated;
    }

    /**
     * @param mixed $photoUpdated
     */
    public function setPhotoUpdated($photoUpdated)
    {
        $this->photoUpdated = $photoUpdated;
    }

    /**
     * @return mixed
     */
    public function getImageHeight()
    {
        return $this->imageHeight;
    }

    /**
     * @param mixed $imageHeight
     */
    public function setImageHeight($imageHeight)
    {
        $this->imageHeight = $imageHeight;
    }

    /**
     * @return mixed
     */
    public function getImageWidth()
    {
        return $this->imageWidth;
    }

    /**
     * @param mixed $imageWidth
     */
    public function setImageWidth($imageWidth)
    {
        $this->imageWidth = $imageWidth;
    }

    /**
     * @return array
     */
    public function getStreams(): array
    {
        return $this->streams;
    }

    /**
     * @param array $streams
     */
    public function setStreams(array $streams)
    {
        $this->streams = $streams;
    }


}
