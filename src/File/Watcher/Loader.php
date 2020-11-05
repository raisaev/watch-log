<?php

declare(strict_types=1);

namespace Isaev\WatchLog\File\Watcher;

class Loader
{
    /** @var array */
    private $files;

    // ########################################

    public function __construct(array $files)
    {
        $this->files = $files;
    }

    // ########################################

    /**
     * @return \Isaev\WatchLog\File\Watcher[]
     */
    public function load(): array
    {
        $result = [];
        foreach ($this->files as $file) {
            $result[] = new \Isaev\WatchLog\File\Watcher($file);
        }

        return $result;
    }

    // ########################################
}
