<?php

declare(strict_types=1);

namespace Isaev\WatchLog;

class WatchLog
{
    /** @var Log\Entity\Parser */
    private $parser;

    /** @var Output */
    private $output;

    /** @var File\Watcher[] */
    private $watchers;

    /** @var Log\Handler\HandlerInterface[] */
    private $handlers;

    /** @var int */
    private $checkInterval;

    /** @var bool */
    private $watch = false;

    /** @var bool */
    private $isDebugMode;

    // ########################################

    public function __construct(
        File\Watcher\Loader $watcherLoader,
        Log\Entity\Parser $parser,
        Output $output,
        array $handlers = [],
        int $checkInterval = 10
    ) {
        $this->parser = $parser;
        $this->output = $output;

        $this->handlers      = $handlers;
        $this->checkInterval = $checkInterval;
        $this->watchers      = $watcherLoader->load();
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

        $this->output->printLn('watching...');
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
                $this->output->printLn('changed: ' . $watcher->getFilePath());
            }

            try {
                while (!$watcher->getResource()->eof()) {
                    $line = $watcher->getResource()->fgets();
                    if ($this->isDebugMode) {
                        $this->output->printLn($line);
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
                $this->output->printLn2($e->__toString());
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
