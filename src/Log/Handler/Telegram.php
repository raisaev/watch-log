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
        $message = <<<TEXT
<b>{$entity->getServiceName()}</b>
{$filePath}

{$entity->getText()} [<b>{$entity->getType()}</b>]
{$entity->getFile()}::{$entity->getLine()}
<code>
{$entity->getTrace()}
</code>
TEXT;

        if (!empty($entity->getExceptionData()['data'])) {
            $data = json_encode($entity->getExceptionData()['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            $message .= <<<TEXT
<code>
{$data}
</code>
TEXT;
        }

        $response = $this->httpClient->request(
            'POST',
            "https://api.telegram.org/bot{$this->token}/sendMessage",
            [
                'body' => [
                    'text'       => $message,
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
}
