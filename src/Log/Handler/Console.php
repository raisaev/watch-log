<?php

declare(strict_types=1);

namespace Isaev\WatchLog\Log\Handler;

class Console implements HandlerInterface
{
    // ########################################

    public function handle(\Isaev\WatchLog\Log\Entity $entity, string $filePath): void
    {
        echo $this->getMessage($entity, $filePath) . PHP_EOL . PHP_EOL;
    }

    // ########################################

    private function getMessage(\Isaev\WatchLog\Log\Entity $entity, string $filePath): string
    {
        $message = <<<TEXT
{$this->colored($entity->getServiceName(), 'green')}
{$filePath} {$entity->getDateTime()->format('Y-m-d H:i')}

{$this->colored($entity->getText(), 'red')}
TEXT;
        if ($entity->getException()) {
            $message .= <<<TEXT

Exception:
{$this->colored($entity->getExceptionMessage(), 'red')}
{$entity->getExceptionFile()}::{$entity->getExceptionLine()}

{$this->colored($entity->getExceptionTrace(), 'yellow')}

TEXT;
            if ($entity->getExceptionData()) {
                $data = json_encode($entity->getExceptionData(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                $message .= <<<TEXT

{$this->colored($data, 'cyan')}
TEXT;
            }
        }

        return $message;
    }

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
