<?php

namespace MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Entity\Team;

/**
 * Unlock a specific team for login.
 *
 * Command usage: app:unlock-team team-slug
 */
class UnlockTeamCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:unlock-team')
            ->addArgument('slug', InputArgument::REQUIRED, 'The team slug is required')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $slug = $input->getArgument('slug');

        $team = $em->getRepository(Team::class)->findOneBySlug($slug);
        if ($team) {
            $team->setAvailable(true);
            $em->persist($team);
            $em->flush();

            $output->writeln('<info>Team unlocked.</info>');
        } else {
            $output->writeln('<info>Team not found.</info>');
        }
    }
}
