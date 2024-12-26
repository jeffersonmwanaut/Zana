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
    protected ImageDimension $imageDimension;

    public function __construct(array $file)
    {
        parent::__construct($file);

        // Validate that the uploaded file is an image
        if (!$this->isImage($file['tmp_name'])) {
            throw new \RuntimeException('Uploaded file is not a valid image.');
        }

        $dimension = getimagesize($file['tmp_name']);
        if ($dimension === false) {
            throw new \RuntimeException('Failed to get image dimensions.');
        }

        $this->imageDimension = new ImageDimension($dimension[0], $dimension[1]);
    }

    /**
     * Check if the uploaded file is an image.
     * @param string $filePath
     * @return bool
     */
    protected function isImage(string $filePath): bool
    {
        $imageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/bmp'];
        $fileType = mime_content_type($filePath);
        return in_array($fileType, $imageTypes);
    }

    /**
     * @return ImageDimension
     */
    public function getImageDimension(): ImageDimension
    {
        return $this->imageDimension;
    }

    /**
     * @param ImageDimension $imageDimension
     * @return Image
     */
    public function setImageDimension(ImageDimension $imageDimension): self
    {
        $this->imageDimension = $imageDimension;
        return $this;
    }
}