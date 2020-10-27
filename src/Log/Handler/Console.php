<?php

declare(strict_types=1);

namespace Isaev\WatchLog\Log\Handler;

class Console implements HandlerInterface
{
    // ########################################

    public function handle(\Isaev\WatchLog\Log\Entity $entity, string $filePath): void
    {
        $message = <<<TEXT
{$this->colored($entity->getServiceName(), 'green')}
{$filePath}

{$this->colored($entity->getText(), 'red')} [{$this->colored($entity->getType(), 'red')}]
{$entity->getFile()}::{$entity->getLine()}

{$this->colored($entity->getTrace(), 'yellow')}

TEXT;

        if (!empty($entity->getExceptionData()['data'])) {
            $data = json_encode($entity->getExceptionData()['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            $message .= <<<TEXT

{$this->colored($data, 'cyan')}
TEXT;
        }

        echo $message . PHP_EOL . PHP_EOL;
    }

    // ########################################

    private function colored(string $string, string $color = 'default')
    {
        $codesMap = [
            'default' => 29,
            'red'     => 31,
            'green'   => 32,
            'yellow'  => 33,
            'cyan'    => 36
        ];
        $colorMark = $codesMap[$color] ?? $codesMap['default'];

        return "\033[{$colorMark}m{$string}\033[0m";
    }

    // ########################################
}
