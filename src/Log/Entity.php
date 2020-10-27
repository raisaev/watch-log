<?php

declare(strict_types=1);

namespace Isaev\WatchLog\Log;

class Entity
{
    /** @var string */
    private $serviceName;

    /** @var string */
    private $text;

    /** @var array */
    private $raw;

    // ########################################

    public function __construct(
        string $serviceName,
        string $text,
        array $raw
    ) {
        $this->serviceName = $serviceName;
        $this->text        = $text;
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
     * @return array
     */
    public function getRaw(): array
    {
        return $this->raw;
    }

    public function getDateTime(): \DateTime
    {
        $date = \DateTime::createFromFormat('Y-m-d\TH:i:s.u\Z',
            $this->raw['create_datetimemc'],
            new \DateTimeZone('UTC')
        );
        $date->setTimezone(new \DateTimeZone('Europe/Kiev'));

        return $date;
    }

    // ########################################

    public function getException(): ?array
    {
        if (!empty($this->raw['data']['data']['_exception_data_'])) {
            return $this->raw['data']['data']['_exception_data_'];
        }

        if (!empty($this->raw['data']['data']['exception_data'])) {
            return $this->raw['data']['data']['exception_data'];
        }

        return null;
    }

    public function getExceptionMessage(): string
    {
        return $this->getException()['message'];
    }

    public function getExceptionType(): string
    {
        return $this->getException()['type'];
    }

    public function getExceptionFile(): string
    {
        return $this->getException()['file'];
    }

    public function getExceptionLine(): int
    {
        return $this->getException()['line'];
    }

    public function getExceptionData(): array
    {
        return $this->getException()['data'];
    }

    public function getExceptionTrace(): string
    {
        if (is_array($this->getException()['trace'])) {
            return implode(PHP_EOL, $this->getException()['trace']);
        }

        return $this->getException()['trace'];
    }

    // ########################################
}
