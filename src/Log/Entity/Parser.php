<?php

declare(strict_types=1);

namespace Isaev\WatchLog\Log\Entity;

class Parser
{
    // ########################################

    public function parseLine(string $raw): ?\Isaev\WatchLog\Log\Entity
    {
        $decoded = (array)json_decode($raw, true);
        if (empty($decoded)) {
            return null;
        }

        return new \Isaev\WatchLog\Log\Entity(
            $decoded['service']['name'],
            $decoded['data']['text'],
            $decoded['data']['type'],
            $decoded['data']['data']['_exception_data_']['file'],
            $decoded['data']['data']['_exception_data_']['line'],
            $decoded['data']['data']['_exception_data_']['trace'],
            $decoded
        );
    }

    // ########################################
}
