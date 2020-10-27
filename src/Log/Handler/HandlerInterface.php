<?php

declare(strict_types=1);

namespace Isaev\WatchLog\Log\Handler;

interface HandlerInterface
{
    // ########################################

    public function handle(\Isaev\WatchLog\Log\Entity $entity, string $filePath): void;

    // ########################################
}
