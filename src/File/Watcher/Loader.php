<?php

declare(strict_types=1);

namespace Isaev\WatchLog\File\Watcher;

class Loader
{
    // ########################################

    /**
     * @return \Isaev\WatchLog\File\Watcher[]
     */
    public function load(): array
    {
        $files = array_filter(explode("\n", $_ENV['LOG_FILES_LIST']));

        $result = [];
        foreach ($files as $file) {
            $result[] = new \Isaev\WatchLog\File\Watcher($file);
        }

        return $result;
    }

    // ########################################
}
