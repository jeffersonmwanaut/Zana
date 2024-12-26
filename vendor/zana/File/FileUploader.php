<?php namespace Zana\File;

/**
 * Class Upload
 * @package Zana\File
 */
class FileUploader
{
    protected File $file;
    protected array $validExtensions;
    protected int $maxSize; // Maximum file size in bytes
    protected string $uploadDir; // Directory where uploaded files will be stored
    protected string $destinationFilename;

    public function __construct(File $file, string $uploadDir, string $destinationFilename = '', array $validExtensions = [], int $maxSize = 1000000)
    {
        $this->file = $file;
        $this->uploadDir = $uploadDir;
        $this->destinationFilename = $destinationFilename;
        $this->validExtensions = $validExtensions;
        $this->maxSize = $maxSize;
    }

    public function setUploadDir(string $uploadDir): self
    {
        $this->uploadDir = $uploadDir;
        return $this;
    }

    public function addExtension(string $extension): self
    {
        $this->validExtensions[] = $extension;
        return $this;
    }

    public function addExtensions(array $extensions): self
    {
        $this->validExtensions = array_merge($this->validExtensions, $extensions);
        return $this;
    }

    public function upload(): string
    {
        if ($this->file->getError() > 0) {
            throw new \RuntimeException($this->file->getErrorMessage());
        }
        
        if (!in_array($this->file->getExtension(), $this->validExtensions)) {
            throw new \RuntimeException('Invalid file extension.');
        }

        $fileSize = $this->file->getSize();
        if ($fileSize > $this->maxSize) {
            throw new \RuntimeException('File size exceeds the maximum limit.');
        }

        // Check if the upload directory exists and is writable
        if (!is_dir($this->uploadDir) || !is_writable($this->uploadDir)) {
            throw new \RuntimeException('Upload directory does not exist or is not writable.');
        }

        // Move the uploaded file to a permanent location
        $tmpFilename = $this->file->getTmpFilename();
        $fileExtension = $this->file->getExtension();
        
        // Sanitize the destination filename
        $this->destinationFilename = $this->sanitizeFilename($this->destinationFilename ?: uniqid() . ".$fileExtension");
        
        $destinationFullPath = $this->uploadDir . '/' . $this->destinationFilename;
        
        if (move_uploaded_file($tmpFilename, $destinationFullPath)) {
            return $destinationFullPath; // return the uploaded file path
        } else {
            throw new \RuntimeException('Failed to move uploaded file.');
        }
    }

    public function setDestinationFilename(string $filename): self
    {
        $this->destinationFilename = $filename;
        return $this;
    }

    public function getDestinationFilename(): string
    {
        return $this->destinationFilename;
    }

    /**
     * Sanitize the filename to ensure it is safe for use in the file system.
     * @param string $filename
     * @return string
     */
    protected function sanitizeFilename(string $filename): string
    {
        // Remove any unwanted characters
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
        
        // Replace multiple underscores with a single one
        $filename = preg_replace('/_+/', '_', $filename);
        
        // Limit the length of the filename
        $maxLength = 255; // Common maximum length for filenames
        if (strlen($filename) > $maxLength) {
            $filename = substr($filename, 0, $maxLength);
        }

        return $filename;
    }

}