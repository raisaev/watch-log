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

    public function handle(\Isaev\WatchLog\Log\Entity $entity): void
    {
        $message = <<<TEXT
service: <b>{$entity->getServiceName()}</b>
{$entity->getText()} [<b>{$entity->getType()}</b>]
{$entity->getFile()}::{$entity->getLine()}
<code>
{$entity->getTrace()}
</code>
TEXT;

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
