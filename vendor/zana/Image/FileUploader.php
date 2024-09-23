<?php namespace Zana\Image;

/**
 * Class Upload
 * @package Zana\Image
 */
class FileUploader
{

    /**
     * @var string[]
     */
    protected $validExtensions = [];
    protected $format = [];
    protected $uploadDir; // directory where uploaded files will be stored

    public function __construct($uploadDir, $validExtensions = [], $format = [])
    {
        $this->uploadDir = $uploadDir;
        $this->validExtensions = $validExtensions;
        $this->format = $format;
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

    public function format($width, $height)
    {
        $this->format = [$width, $height];
    }

    public function upload()
    {
        if ($this->error() > 0) {
            return false;
        }
        if (!in_array($this->getFileExtension(), $this->validExtensions)) {
            return false;
        }
        $fileDimensions = $this->getFileDimensions();
        if ($fileDimensions[0] > $this->format[0] || $fileDimensions[1] > $this->format[1]) {
            return false;
        }

        // Move the uploaded file to a permanent location
        $fileTmpName = $this->getFileTmpName();
        $fileExtension = $this->getFileExtension();
        $destination = $this->uploadDir . uniqid() . ".$fileExtension";
        if (move_uploaded_file($fileTmpName, $destination)) {
            return $destination; // return the uploaded file path
        } else {
            return false;
        }
    }

    protected function getClientFileName()
    {
        return $_FILES['file']['name'];
    }

    protected function getFileType()
    {
        return $_FILES['file']['type'];
    }

    protected function getFileSize()
    {
        return $_FILES['file']['size'];
    }

    protected function getFileDimensions()
    {
        return getimagesize($_FILES['file']['tmp_name']);
    }

    protected function error()
    {
        return $_FILES['file']['error'];
    }

    protected function getFileTmpName()
    {
        return $_FILES['file']['tmp_name'];
    }

    protected function getFileExtension()
    {
        return strtolower(  substr(  strrchr($_FILES['icone']['name'], '.')  ,1)  );
    }

}