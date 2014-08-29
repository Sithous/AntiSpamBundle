<?php

namespace Sithous\AntiSpamBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GarbageCollectorCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('sithous:antispam:gc')
            ->setDescription('Run AntiSpam garbage collection.')
            ->addOption('time', null, InputOption::VALUE_NONE, 'If set, the task will be timed');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $antiSpamService = $this->getContainer()->get('sithous.antispam');

        $start_time = microtime(true);
        $antiSpamService->_garbage_collector();

        if($input->getOption('time'))
        {
            $output->writeln('Execution time: '.(microtime(true) - $start_time));
        }

        $output->writeln('Complete.');
    }
}