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
    protected $clientFilename;
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
    protected $tmpFilename;
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
        $this->clientFilename = isset($file['name']) ? $file['name'] : '';
        $this->clientMediaType = isset($file['type']) ? $file['type'] : '';
        $this->error = isset($file['error']) ? $file['error'] : '';
        $this->tmpFilename = isset($file['tmp_name']) ? $file['tmp_name'] : '';
        $this->size = isset($file['size']) ? $file['size'] : '';
        $this->extension = isset($file['name']) ? strtolower(  substr(  strrchr($file['name'], '.')  ,1)  ) : '';
    }

    /**
     * @return string
     */
    public function getClientFilename()
    {
        return $this->clientFilename;
    }

    /**
     * @param string $clientFilename
     * @return File
     */
    public function setClientFilename($clientFilename)
    {
        $this->clientFilename = $clientFilename;
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
    public function getTmpFilename()
    {
        return $this->tmpFilename;
    }

    /**
     * @param string $file
     * @return File
     */
    public function setTmpFilename($tmpFilename)
    {
        $this->tmpFilename = $tmpFilename;
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