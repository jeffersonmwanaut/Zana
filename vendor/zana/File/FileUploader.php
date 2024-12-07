<?php namespace Zana\File;

/**
 * Class Upload
 * @package Zana\File
 */
class FileUploader
{
    protected File $file;
    protected $validExtensions;
    protected $maxSize; // 1000 = 1KB
    protected $uploadDir; // directory where uploaded files will be stored
    protected $destinationFilename;

    public function __construct($file, $uploadDir, $destinationFilename = '', $validExtensions = [], $maxSize = 1000000)
    {
        $this->file = $file;
        $this->uploadDir = $uploadDir;
        $this->destinationFilename = $destinationFilename;
        $this->validExtensions = $validExtensions;
        $this->maxSize = $maxSize;
    }

    public function setUploadDir($uploadDir)
    {
        $this->uploadDir = $uploadDir;
        return $this;
    }

    /**
     * @param string $extension
     */
    public function addExtension($extension)
    {
        $this->validExtensions[] = $extension;
        return $this;
    }

    /**
     * @param string[] $extensions
     */
    public function addExtensions(array $extensions)
    {
        $this->validExtensions = array_merge($this->validExtensions, $extensions);
        return $this;
    }

    public function upload()
    {
        if ($this->file->getError() > 0) {
            return false;
        }
        if (!in_array($this->file->getExtension(), $this->validExtensions)) {
            return false;
        }
        $fileSize = $this->file->getSize();
        if ($fileSize > $this->maxSize) {
            return false;
        }

        // Move the uploaded file to a permanent location
        $tmpFilename = $this->file->getTmpFilename();
        $fileExtension = $this->file->getExtension();
        if(empty($this->destinationFilename)) {
            $this->destinationFilename = uniqid() . ".$fileExtension";
        } else {
            $this->destinationFilename = $this->destinationFilename . ".$fileExtension";
        }
        $destinationFullPath = $this->uploadDir . '/' . $this->destinationFilename;
        if (move_uploaded_file($tmpFilename, $destinationFullPath)) {
            return $destinationFullPath; // return the uploaded file path
        } else {
            return false;
        }
    }

    public function setDestinationFilename($filename)
    {
        $this->destinationFilename = $filename;
        return $this;
    }

    public function getDestinationFilename()
    {
        return $this->destinationFilename;
    }

}