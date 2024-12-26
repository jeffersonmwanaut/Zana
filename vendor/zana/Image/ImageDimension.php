<?php
/**
 * Created by PhpStorm.
 * User: Gatiet Mwanaut J
 * Date: 31/12/2018
 * Time: 13:29
 */

namespace Zana\Image;

/**
 * Class ImageDimension
 * @package Zana\Image
 */
class ImageDimension
{
    /**
     * @var int
     */
    protected $width;
    /**
     * @var int
     */
    protected $height;

    /**
     * ImageDimension constructor.
     * @param int $width
     * @param int $height
     */
    public function __construct($width, $height)
    {
        if ($width <= 0 || $height <= 0) {
            throw new \InvalidArgumentException('Width and height must be positive integers.');
        }
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @param int $width
     * @return ImageDimension
     */
    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @param int $height
     * @return ImageDimension
     */
    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }

}