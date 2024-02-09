<?php

namespace Zana\File;

/**
 * Class UploadedFile
 * @package Kdec\File
 */
class File
{

    /**
     * @var string
     */
    protected $clientFileName;
    /**
     * @var string
     */
    protected $clientMediaType;
    /**
     * @var int
     */
    protected $error;
    /**
     * @var string
     */
    protected $file;
    /**
     * @var int
     */
    protected $size;
    /**
     * @var string
     */
    protected $extension;

    public function __construct(array $file)
    {
        $this->clientFileName = isset($file['name']) ? $file['name'] : '';
        $this->clientMediaType = isset($file['type']) ? $file['type'] : '';
        $this->error = isset($file['error']) ? $file['error'] : '';
        $this->file = isset($file['tmp_name']) ? $file['tmp_name'] : '';
        $this->size = isset($file['size']) ? $file['size'] : '';
        $this->extension = isset($file['name']) ? strtolower(  substr(  strrchr($file['name'], '.')  ,1)  ) : '';
    }

    /**
     * @return string
     */
    public function getClientFileName()
    {
        return $this->clientFileName;
    }

    /**
     * @param string $clientFileName
     * @return File
     */
    public function setClientFileName($clientFileName)
    {
        $this->clientFileName = $clientFileName;
        return $this;
    }

    /**
     * @return string
     */
    public function getClientMediaType()
    {
        return $this->clientMediaType;
    }

    /**
     * @param string $clientMediaType
     * @return File
     */
    public function setClientMediaType($clientMediaType)
    {
        $this->clientMediaType = $clientMediaType;
        return $this;
    }

    /**
     * @return int
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param int $error
     * @return File
     */
    public function setError($error)
    {
        $this->error = $error;
        return $this;
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param string $file
     * @return File
     */
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param int $size
     * @return File
     */
    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param string $extension
     * @return File
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
        return $this;
    }

}