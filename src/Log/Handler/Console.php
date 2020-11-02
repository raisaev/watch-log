<?php

declare(strict_types=1);

namespace Isaev\WatchLog\Log\Handler;

class Console implements HandlerInterface
{
    /** @var \Isaev\WatchLog\Output */
    private $output;

    // ########################################

    public function __construct(
        \Isaev\WatchLog\Output $output
    ) {
        $this->output = $output;
    }

    // ########################################

    public function handle(\Isaev\WatchLog\Log\Entity $entity, string $filePath): void
    {
        $this->output->printLn2($this->getMessage($entity, $filePath));
    }

    // ########################################

    private function getMessage(\Isaev\WatchLog\Log\Entity $entity, string $filePath): string
    {
        $message = <<<TEXT
{$this->output->format($entity->getServiceName(), 'green')}
{$this->output->format($filePath, 'green')} {$this->output->format($entity->getDateTime()->format('Y-m-d H:i'), 'green')}

{$this->output->format($entity->getText(), 'red')}
TEXT;
        if ($entity->getException()) {
            $message .= <<<TEXT


Exception:
{$this->output->format($entity->getExceptionMessage(), 'red')}
{$this->output->format($entity->getExceptionFile() . '::' . $entity->getExceptionLine(), 'cyan')}

{$this->output->format($entity->getExceptionTrace(), 'yellow')}

TEXT;
            if ($entity->getExceptionData()) {
                $data = json_encode($entity->getExceptionData(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                $message .= <<<TEXT

{$this->output->format($data, 'cyan')}
TEXT;
            }
        }

        return $message;
    }

    // ########################################
}
