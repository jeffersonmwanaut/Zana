<?php namespace Zana\Image;

use Zana\File\File;

/**
 * Class Image
 * @package Zana\Image
 */
class Image extends File
{
    /**
     * @var ImageDimension
     */
    protected $imageDimension;

    public function __construct(array $file)
    {
        parent::__construct($file);
        $dimension = getimagesize($file['tmp_name']);
        $this->imageDimension = new ImageDimension($dimension[0], $dimension[1]);
    }

    /**
     * @return ImageDimension
     */
    public function getImageDimension()
    {
        return $this->imageDimension;
    }

    /**
     * @param ImageDimension $imageDimension
     * @return Image
     */
    public function setImageDimension($imageDimension)
    {
        $this->imageDimension = $imageDimension;
        return $this;
    }
}