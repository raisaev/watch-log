<?php

declare(strict_types=1);

namespace Isaev\WatchLog;

class WatchLog
{
    /** @var File\Watcher[] */
    private $watchers;

    /** @var Log\Handler\HandlerInterface[] */
    private $handlers;

    /** @var int */
    private $checkInterval;

    /** @var bool */
    private $watch = false;

    /** @var Log\Entity\Parser */
    private $parser;

    /** @var bool */
    private $isDebugMode;

    // ########################################

    public function __construct(
        File\Watcher\Loader $watcherLoader,
        Log\Entity\Parser $parser,
        array $handlers = [],
        int $checkInterval = 10
    ) {
        $this->handlers      = $handlers;
        $this->checkInterval = $checkInterval;

        $this->watchers = $watcherLoader->load();
        $this->parser   = $parser;
    }

    // ########################################

    public function start(): void
    {
        if ($this->watch) {
            throw new Exception('Is already run.');
        }

        if (empty($this->watchers)) {
            throw new Exception('No watchers registered.');
        }

        if (empty($this->handlers)) {
            throw new Exception('No handlers registered.');
        }

        echo 'watching...' . PHP_EOL . PHP_EOL;
        $this->watch = true;

        do {
            $this->check();
            sleep($this->checkInterval);
        } while ($this->watch);
    }

    public function stop(): void
    {
        $this->watch = false;
    }

    // ########################################

    private function check(): void
    {
        foreach ($this->watchers as $watcher) {
            if (!$watcher->checkChange()) {
                continue;
            }

            if ($this->isDebugMode) {
                echo 'changed: ' . $watcher->getFilePath() . PHP_EOL;
            }

            try {
                while (!$watcher->getResource()->eof()) {
                    $line = $watcher->getResource()->fgets();
                    if ($this->isDebugMode) {
                        echo $line . PHP_EOL;
                    }

                    $entity = $this->parser->parseLine($line);
                    if ($entity === null) {
                        continue;
                    }

                    foreach ($this->handlers as $handler) {
                        $handler->handle($entity, $watcher->getFilePath());
                    }
                }

            } catch (\Throwable $e) {
                echo $e->__toString() . PHP_EOL . PHP_EOL;
            }
        }
    }

    // ########################################

    public function addWatcher(File\Watcher $watcher): self
    {
        $this->watchers[] = $watcher;
        return $this;
    }

    public function addHandler(Log\Handler\HandlerInterface $handler): self
    {
        $this->handlers[] = $handler;
        return $this;
    }

    public function setIsDebugMode(bool $mode): self
    {
        $this->isDebugMode = $mode;
        return $this;
    }

    // ########################################
}
