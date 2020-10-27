<?php

namespace Isaev\WatchLog\File;

class Watcher
{
    /** @var string */
    private $filePath;

    // ----------------------------------------

    /** @var \SplFileInfo|null  */
    private $fileInfo;

    /** @var \SplFileObject|null */
    private $resource;

    /** @var int|null */
    private $lastModified;

    // ########################################

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;

        if (!is_file($this->filePath)) {
            return;
        }

        $this->fileInfo = new \SplFileInfo($this->filePath);
        $this->resource = $this->fileInfo->openFile('r');
        $this->resource->fseek(0, SEEK_END);

        $this->lastModified = $this->fileInfo->getMTime();
    }

    // ########################################

    public function checkChange(): bool
    {
        clearstatcache(true, $this->filePath);

        if ($this->getFileInfo() === null) {
            if (is_file($this->filePath)) {
                $this->fileInfo = new \SplFileInfo($this->filePath);
                $this->resource = $this->fileInfo->openFile('r');

                $this->lastModified = $this->fileInfo->getMTime();
                return true;
            }

            return false;
        }

        $lastModified = $this->fileInfo->getMTime();
        if ($this->lastModified === $lastModified) {
            return false;
        }

        $this->lastModified = $lastModified;
        return true;
    }

    // ########################################

    public function getFilePath(): string
    {
        return $this->filePath;
    }

    // ----------------------------------------

    public function getFileInfo(): ?\SplFileInfo
    {
        return $this->fileInfo;
    }

    public function getResource(): ?\SplFileObject
    {
        return $this->resource;
    }

    public function getLastModified(): ?int
    {
        return $this->lastModified;
    }

    // ########################################
}
