<?php

namespace Zana\File;

/**
 * Class UploadedFile
 * @package Kdec\File
 */
class File
{

    protected string $clientFilename;
    protected string $clientMediaType;
    protected int $error;
    protected string $tmpFilename;
    protected int $size;
    protected string $extension;

    public function __construct(array $file)
    {
        $this->clientFilename = $file['name'] ?? '';
        $this->clientMediaType = $file['type'] ?? '';
        $this->error = $file['error'] ?? 0;
        $this->tmpFilename = $file['tmp_name'] ?? '';
        $this->size = $file['size'] ?? 0;
        $this->extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    }

    public function getClientFilename(): string
    {
        return $this->clientFilename;
    }

    public function setClientFilename(string $clientFilename): self
    {
        $this->clientFilename = $clientFilename;
        return $this;
    }

    public function getClientMediaType(): string
    {
        return $this->clientMediaType;
    }

    public function setClientMediaType(string $clientMediaType): self
    {
        $this->clientMediaType = $clientMediaType;
        return $this;
    }

    public function getError(): int
    {
        return $this->error;
    }

    public function setError(int $error): self
    {
        $this->error = $error;
        return $this;
    }

    public function getTmpFilename(): string
    {
        return $this->tmpFilename;
    }

    public function setTmpFilename(string $tmpFilename): self
    {
        $this->tmpFilename = $tmpFilename;
        return $this;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;
        return $this;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function setExtension(string $extension): self
    {
        $this->extension = $extension;
        return $this;
    }

    /**
     * Get a human-readable error message based on the error code.
     * @return string
     */
    public function getErrorMessage(): string
    {
        switch ($this->error) {
            case UPLOAD_ERR_OK:
                return 'No error, the file uploaded successfully.';
            case UPLOAD_ERR_INI_SIZE:
                return 'The uploaded file exceeds the upload_max_filesize directive in php.ini.';
            case UPLOAD_ERR_FORM_SIZE:
                return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.';
            case UPLOAD_ERR_PARTIAL:
                return 'The uploaded file was only partially uploaded.';
            case UPLOAD_ERR_NO_FILE:
                return 'No file was uploaded.';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Missing a temporary folder.';
            case UPLOAD_ERR_CANT_WRITE:
                return 'Failed to write file to disk.';
            case UPLOAD_ERR_EXTENSION:
                return 'A PHP extension stopped the file upload.';
            default:
                return 'Unknown upload error.';
        }
    }

    /**
     * Move the uploaded file to a specified destination.
     * @param string $destination
     * @return bool
     */
    public function moveTo(string $destination): bool
    {
        if ($this->error !== UPLOAD_ERR_OK) {
            return false;
        }
        return move_uploaded_file($this->tmpFilename, $destination);
    }

}