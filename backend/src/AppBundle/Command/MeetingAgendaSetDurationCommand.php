<?php

namespace AppBundle\Command;

use AppBundle\Entity\MeetingAgenda;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Meeting agenda - set duration based on start and finish date.
 *
 * Command usage: app:meeting-agenda:set:duration
 */
class MeetingAgendaSetDurationCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:meeting-agenda:set:duration')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        $meetingAgendas = $em
            ->getRepository(MeetingAgenda::class)
            ->findAll()
        ;
        if (count($meetingAgendas)) {
            $output->writeln('<info>MeetingAgenda-Set duration based on start and finish hours --> Start</info>');
            foreach ($meetingAgendas as $agenda) {
                $duration = ($agenda->getEnd()->getTimestamp() - $agenda->getStart()->getTimestamp()) / 60;
                if ($duration < 0) {
                    $duration = 0;
                }
                $agenda->setDuration($duration);
            }
            $em->flush();

            $output->writeln('<info>MeetingAgenda-Set duration based on start and finish hours --> Finish</info>');
        } else {
            $output->writeln('<info>There is no meeting agenda</info>');
        }
    }
}
