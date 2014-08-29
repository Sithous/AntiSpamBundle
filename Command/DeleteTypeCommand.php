<?php

namespace Sithous\AntiSpamBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Sithous\AntiSpamBundle\Entity\SithousAntiSpamType;
use Doctrine\Common\Collections\ArrayCollection;


class DeleteTypeCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('sithous:antispam:delete')
            ->setDescription('Generate new SithousAntiSpamType.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $repository = $em->getRepository('SithousAntiSpamBundle:SithousAntiSpamType');
        $dialog = $this->getHelper('dialog');

        $types = array();
        foreach($repository->findAll() as $type)
        {
            $types[] = $type->getId();
        }

        if(!$id = $dialog->ask(
            $output,
            '<question>Enter the SithousAntiSpamType ID to delete:</question> ',
            false,
            $types
        ))
        {
            return $output->writeln('<error>ERROR: An ID must be specified.</error>');
        }

        if(!$entity = $repository->findOneById($id))
        {
            return $output->writeln("<error>ERROR: SithousAntiSpamType with ID \"$id\" could not be found.</error>");
        }

        $em->remove($entity);
        $em->flush();

        return $output->writeln("<info>Successfully removed SithousAntiSpamType \"$id\"</info>");
    }
}