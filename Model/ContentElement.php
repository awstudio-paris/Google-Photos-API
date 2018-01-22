<?php

namespace GooglePhotosApi\Model;

use SimpleXMLElement;

/**
 * The object model of a GooglePhoto video stream or photo.
 */
class ContentElement extends AbstractMedia
{
    /**
     * @var string
     */
    protected $url = '';

    /**
     * @var int
     */
    protected $height = 0;

    /**
     * @var int
     */
    protected $width = 0;

    /**
     * @var string
     */
    protected $contentType = null;

    /**
     * @var string
     */
	protected $medium = null;

	/**
	 * Make stream representation from an XML <media:content> tag
	 * returned by the API.
	 *
	 * @param SimpleXMLElement $entry
	 * @param array $settings
	 *
	 * @return AbstractMedia
	 */
	public static function makeFromXml(SimpleXMLElement $entry, $settings)
	{
		$contentElement = new ContentElement();

		$attributes = $entry->attributes();
		$contentElement->setUrl((string) $attributes['url']);
		$contentElement->setHeight((int) $attributes['height']);
		$contentElement->setWidth((int) $attributes['width']);
		$contentElement->setContentType((string) $attributes['type']);
		$contentElement->setMedium((string) $attributes['medium']);

		return $contentElement;
	}

	/**
	 * Tell if the element dimensions are greater than an other
	 * element.
	 *
	 * @param ContentElement $otherContent
	 * @return boolean
	 */
	public function isBiggerThan(ContentElement $otherContent)
	{
		return $this->getWidth() > $otherContent->getWidth();
	}

    /**
     * Check if the element is a video stream
     *
     * @param SimpleXMLElement $entry
     * @return bool
     */
	public static function isVideo(SimpleXMLElement $entry)
	{
		return $entry->attributes()['medium'] === 'video';
	}


    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int $height
     */
    public function setHeight( $height)
    {
        $this->height = $height;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * @return null
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @param null $contentType
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
    }

    /**
     * @return null
     */
    public function getMedium()
    {
        return $this->medium;
    }

    /**
     * @param null $medium
     */
    public function setMedium($medium)
    {
        $this->medium = $medium;
    }


}
