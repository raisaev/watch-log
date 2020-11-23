<?php

namespace Isaev\WatchLog\File;

class Watcher
{
    /** @var string */
    private $filePath;

    // ----------------------------------------

    /** @var \SplFileInfo|null  */
    private $fileInfo;

    /** @var int|null */
    private $lastModified;

    /** @var int|null */
    private $lastPosition;

    // ########################################

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;

        if (!is_file($this->filePath)) {
            return;
        }

        $this->fileInfo = new \SplFileInfo($this->filePath);
        $resource = $this->fileInfo->openFile('r');
        $resource->fseek(0, SEEK_END);

        $this->lastModified = $this->fileInfo->getMTime();
        $this->lastPosition = $resource->ftell();
    }

    // ########################################

    public function checkChange(): bool
    {
        clearstatcache(true, $this->filePath);

        if ($this->fileInfo === null) {
            if (is_file($this->filePath)) {
                $this->fileInfo = new \SplFileInfo($this->filePath);
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

    public function getChange(): \Generator
    {
        $handler = $this->fileInfo->openFile('r');
        $this->lastPosition && $handler->fseek($this->lastPosition);

        while (!$handler->eof()) {
            $line = $handler->fgets();
            $this->lastPosition = $handler->ftell();
            yield $line;
        }
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

    public function getLastModified(): ?int
    {
        return $this->lastModified;
    }

    public function getLastPosition(): ?int
    {
        return $this->lastPosition;
    }

    // ########################################
}
