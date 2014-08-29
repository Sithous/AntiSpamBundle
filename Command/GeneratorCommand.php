<?php

namespace Sithous\AntiSpamBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Sithous\AntiSpamBundle\Entity\SithousAntiSpamType;


class GeneratorCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('sithous:antispam:generate')
            ->setDescription('Generate new SithousAntiSpamType.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $repository = $em->getRepository('SithousAntiSpamBundle:SithousAntiSpamType');
        $dialog = $this->getHelper('dialog');

        if(!$id = $dialog->ask(
            $output,
            '<question>Please enter the ID for this type:</question> ',
            false
        ))
        {
            return $output->writeln('<error>ERROR: ID cannot be blank.</error>');
        }

        if($repository->findOneById($id))
        {
            return $output->writeln('<error>ERROR: SithousAntiSpamType with this ID already exists.</error>');
        }

        $trackIp = $dialog->askConfirmation(
            $output,
            '<question>Track IP [Y/N]?</question> ',
            false
        ) == 'y';

        $trackUser = $dialog->askConfirmation(
            $output,
            '<question>Track User [Y/N]?</question> ',
            false
        ) == 'y';

        $maxTime = $dialog->ask(
            $output,
            '<question>Max Time to track action (seconds):</question> ',
            false
        );

        if(!$maxTime || $maxTime < 1)
        {
            return $output->writeln('<error>ERROR: Max Time must be >= 1.</error>');
        }

        $maxCalls = $dialog->ask(
            $output,
            '<question>Max Calls that can happin in MaxTime:</question> ',
            false
        );

        if(!$maxCalls || $maxCalls < 1)
        {
            return $output->writeln('<error>ERROR: Max Calls must be >= 1.</error>');
        }

        $output->writeln("");
        $output->writeln("ID: $id");
        $output->writeln("trackIp: ".($trackIp ? 'true' : 'false'));
        $output->writeln("trackUser: ".($trackUser ? 'true' : 'false'));
        $output->writeln("maxTime: $maxTime seconds");
        $output->writeln("maxCalls: $maxCalls");
        $output->writeln("");

        if(!$dialog->askConfirmation(
            $output,
            '<question>Is the above information correct [Y/N]?</question> ',
            false
        ) == 'y')
        {
            return $output->writeln('<error>You have cancelled.</error>');
        }

        $entity = new SithousAntiSpamType();
        $entity
            ->setId($id)
            ->setTrackIp($trackIp)
            ->setTrackUser($trackUser)
            ->setMaxTime($maxTime)
            ->setMaxCalls($maxCalls);

        $em->persist($entity);
        $em->flush();

        return $output->writeln("<info>Successfully added SithousAntiSpamType \"$id\"</info>");
    }
}