<?php

namespace Isaev\WatchLog\File;

class Watcher
{
    /** @var \SplFileInfo  */
    private $fileInfo;

    /** @var int */
    private $lastModified;

    /** @var \SplFileObject */
    private $resource;

    // ########################################

    public function __construct(\SplFileInfo $fileInfo)
    {
        $this->fileInfo = $fileInfo;
        $this->resource = $fileInfo->openFile('r');
        $this->resource->fseek(0, SEEK_END);

        $this->updateLastModified();
    }

    // ########################################

    public function checkChange(): bool
    {
        clearstatcache(true, $this->fileInfo->getRealPath());

        $lastModified = $this->lastModified;
        $this->updateLastModified();

        if ($lastModified === $this->lastModified) {
            return false;
        }

        return true;
    }

    public function updateLastModified(): void
    {
        $this->lastModified = $this->fileInfo->getMTime();
    }

    public function updateResource(): void
    {
        unset($this->resource);
        $this->resource = $this->fileInfo->openFile('r');
        $this->resource->fseek(0, SEEK_END);
    }

    // ########################################

    public function getFileInfo(): \SplFileInfo
    {
        return $this->fileInfo;
    }

    public function getResource(): \SplFileObject
    {
        return $this->resource;
    }

    public function getLastModified(): int
    {
        return $this->lastModified;
    }

    // ########################################
}
