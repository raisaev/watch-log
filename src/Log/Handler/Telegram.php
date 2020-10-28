<?php

declare(strict_types=1);

namespace Isaev\WatchLog\Log\Handler;

use Isaev\WatchLog\Exception;

class Telegram implements HandlerInterface
{
    /** @var string */
    private $token;

    /** @var string */
    private $chatId;

    /** @var \Symfony\Contracts\HttpClient\HttpClientInterface */
    private $httpClient;

    // ########################################

    public function __construct(
        string $token,
        string $chatId,
        \Symfony\Contracts\HttpClient\HttpClientInterface $httpClient
    ) {
        $this->token  = $token;
        $this->chatId = $chatId;

        $this->httpClient = $httpClient;
    }

    // ########################################

    public function handle(\Isaev\WatchLog\Log\Entity $entity, string $filePath): void
    {
        $response = $this->httpClient->request(
            'POST',
            "https://api.telegram.org/bot{$this->token}/sendMessage",
            [
                'body' => [
                    'text'       => $this->getMessage($entity, $filePath),
                    'chat_id'    => $this->chatId,
                    'parse_mode' => 'HTML'
                ]
            ]
        );

        $result = $response->toArray();
        if (!empty($result['description'])) {
            throw new Exception($result['description']);
        }
    }

    // ########################################

    private function getMessage(\Isaev\WatchLog\Log\Entity $entity, string $filePath): string
    {
        $message = <<<TEXT
<b>{$entity->getServiceName()}</b>
{$filePath}

{$entity->getText()}
TEXT;

        if ($entity->getException()) {
            $message .= <<<TEXT

{$entity->getExceptionMessage()}
{$entity->getExceptionFile()}::{$entity->getExceptionLine()}
<code>
{$entity->getExceptionTrace()}
</code>
TEXT;
            if ($entity->getExceptionData()) {
                $data = json_encode($entity->getExceptionData(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                $message .= <<<TEXT
<code>
{$data}
</code>
TEXT;
            }
        }

        return $message;
    }

    // ########################################
}
