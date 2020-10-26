<?php

declare(strict_types=1);

namespace Isaev\WatchLog\Log\Handler;

class Console implements HandlerInterface
{
    // ########################################

    public function handle(\Isaev\WatchLog\Log\Entity $entity): void
    {
        $message = <<<TEXT
service: {$entity->getServiceName()}>
{$entity->getText()} [{$entity->getType()}]
{$entity->getFile()}::{$entity->getLine()}

{$entity->getTrace()}

TEXT;

        echo $message . PHP_EOL . PHP_EOL;
    }

    // ########################################
}
