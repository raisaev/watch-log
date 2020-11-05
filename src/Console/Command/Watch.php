<?php

declare(strict_types=1);

namespace Isaev\WatchLog\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Watch extends \Symfony\Component\Console\Command\Command
{
    /** @var \Isaev\WatchLog\WatchLog */
    private $watchLog;

    // ########################################

    public function __construct(
        \Isaev\WatchLog\WatchLog $watchLog,
        string $name = null
    ) {
        $this->watchLog = $watchLog;
        parent::__construct($name);
    }

    // ########################################

    protected function configure()
    {
        $this
            ->setName('logs:watch')
            ->addOption('debug', null, InputOption::VALUE_OPTIONAL, 'Enable debug mode')
            ->setDescription('Watches log files for changes and notifies')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->hasOption('debug') && $input->getOption('debug')) {
            $this->watchLog->setIsDebugMode(true);
        }

        $this->watchLog->start();
    }

    // ########################################
}
