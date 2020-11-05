<?php

declare(strict_types=1);

namespace Isaev\WatchLog\Console;

class Application
{
    /** @var \Symfony\Component\Console\Application */
    private $app;

    /** @var \Symfony\Component\Console\Command\Command[] */
    private $commands;

    // ########################################

    public function __construct(
        string $name,
        array $commands
    ) {
        $this->app = new \Symfony\Component\Console\Application($name);
        $this->commands = $commands;
    }

    // ########################################

    public function run(): void
    {
        foreach ($this->commands as $command) {
            /** @var \Symfony\Component\Console\Command\Command $itemObject */
            $this->app->add($command);
        }

        $this->app->run();
    }

    // ########################################
}
