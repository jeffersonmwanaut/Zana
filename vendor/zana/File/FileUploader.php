<?php namespace Zana\File;

/**
 * Class Upload
 * @package Zana\File
 */
class FileUploader
{
    protected File $file;
    protected $validExtensions;
    protected $maxSize;
    protected $uploadDir; // directory where uploaded files will be stored

    public function __construct($file, $uploadDir, $validExtensions = [], $maxSize = 1000)
    {
        $this->file = $file;
        $this->uploadDir = $uploadDir;
        $this->validExtensions = $validExtensions;
        $this->maxSize = $maxSize;
    }

    public function setUploadDir($uploadDir)
    {
        $this->uploadDir = $uploadDir;
    }

    /**
     * @param string $extension
     */
    public function addExtension($extension)
    {
        $this->validExtensions[] = $extension;
    }

    /**
     * @param string[] $extensions
     */
    public function addExtensions(array $extensions)
    {
        $this->validExtensions = array_merge($this->validExtensions, $extensions);
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
        $fileTmpName = $this->file->getTmpFileName();
        $fileExtension = $this->file->getExtension();
        $destination = $this->uploadDir . '/' . uniqid() . ".$fileExtension";
        if (move_uploaded_file($fileTmpName, $destination)) {
            return $destination; // return the uploaded file path
        } else {
            return false;
        }
    }

    protected function error()
    {
        return $_FILES['file']['error'];
    }

    protected function getFileTmpName()
    {
        return $_FILES['file']['tmp_name'];
    }

}