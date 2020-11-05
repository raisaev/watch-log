<?php

declare(strict_types=1);

namespace Isaev\WatchLog\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Clear extends \Symfony\Component\Console\Command\Command
{
    /** @var \Isaev\WatchLog\File\Watcher\Loader */
    private $watchersLoader;

    // ########################################

    public function __construct(
        \Isaev\WatchLog\File\Watcher\Loader $watcherLoader,
        string $name = null
    ) {
        $this->watchersLoader = $watcherLoader;
        parent::__construct($name);
    }

    // ########################################

    protected function configure()
    {
        $this
            ->setName('logs:clear')
            ->setDescription('Clears log files')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Clearing...</info>');

        foreach ($this->watchersLoader->load() as $watcher) {
            if ($watcher->getFileInfo()) {
                $output->writeln("<comment>{$watcher->getFilePath()}</comment>");
                file_put_contents($watcher->getFilePath(), '--');
            }
        }

        $output->writeln('<info>Done</info>');
        return 0;
    }

    // ########################################
}
