<?php

namespace GooglePhotosApi\Model;

use SimpleXMLElement;

/**
 * The object model of a GooglePhoto image
 */
class Photo extends AbstractMedia
{
	protected $photoTitle;
	protected $photoUrl;
	protected $thumbUrl;
	protected $photoSummary;
	protected $photoPublished;
	protected $photoUpdated;
	protected $imageHeight;
	protected $imageWidth;


    /**
	 * @inheritDoc AbstractMedia
	 */
	public static function makeFromXml(SimpleXMLElement $entry, $settings)
	{
		$photo = new Photo();

		$namespaces = $entry->getNameSpaces(true);
		$gPhoto = $entry->children($namespaces['gphoto']);
		$media = $entry->children($namespaces['media']);
		$thumbnailAttr = $media->group->thumbnail->attributes();
		$photoAttr = $media->group->content->attributes();

		$photo->setPhotoTitle((string) $entry->title[0]);
		$photo->setPhotoUrl((string) $photoAttr['url']);
		$photo->setThumbUrl((string) $thumbnailAttr['url']);
		$photo->setPhotoSummary((string) $entry->summary[0]);
		$photo->setPhotoPublished((string) $entry->published);
		$photo->setPhotoUpdated((string) $entry->updated);
		$photo->setImageHeight((int) $gPhoto->height);
		$photo->setImageWidth((int) $gPhoto->width);

		$photo->setthumbUrl(self::resizeImage(
			$photo->getThumbUrl(),
			$settings['thumb_size'],
			$settings['crop_mode'],
			$settings['should_crop']
		));

		return $photo;
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


}
