<?php

declare(strict_types=1);

namespace Isaev\WatchLog\Log;

class Entity
{
    /** @var string */
    private $serviceName;

    /** @var string */
    private $text;

    /** @var string */
    private $type;

    /** @var string */
    private $file;

    /** @var int */
    private $line;

    /** @var array */
    private $trace;

    /** @var array */
    private $raw;

    // ########################################

    public function __construct(
        string $serviceName,
        string $text,
        string $type,
        string $file,
        int $line,
        array $trace,
        array $raw
    ) {
        $this->serviceName = $serviceName;
        $this->text        = $text;
        $this->type        = $type;
        $this->file        = $file;
        $this->line        = $line;
        $this->trace       = $trace;
        $this->raw         = $raw;
    }

    // ########################################

    /**
     * @return string
     */
    public function getServiceName(): string
    {
        return $this->serviceName;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * @return int
     */
    public function getLine(): int
    {
        return $this->line;
    }

    /**
     * @return string
     */
    public function getTrace(): string
    {
        return implode(PHP_EOL, $this->trace);
    }

    /**
     * @return array
     */
    public function getRaw(): array
    {
        return $this->raw;
    }

    // ########################################
}
