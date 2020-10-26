<?php

declare(strict_types=1);

namespace Isaev\WatchLog;

class Exception extends \Exception
{
    /** @var array */
    private $additionalData;

    // ########################################

    public function __construct(
        $message = '',
        $code = 0,
        $additionalData = [],
        \Throwable $previous = null
    ) {
        $this->additionalData = $additionalData;
        parent::__construct($message, $code, $previous);
    }

    // ########################################

    /**
     * @return array
     */
    public function getAdditionalData(): array
    {
        return $this->additionalData;
    }

    // ########################################
}
