<?php

namespace AppBundle\Command;

use AppBundle\Entity\WorkPackage;
use AppBundle\Event\WorkPackageEvent;
use AppBundle\Services\WorkPackageRasciSync;
use Component\Repository\RepositoryInterface;
use Component\WorkPackage\WorkPackageEvents;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Sync Workpakages RASCI.
 *
 * Command usage: app:project-import MediaEntityId
 */
class WorkPackagesRasciSyncCommand extends ContainerAwareCommand
{
    /**
     * @var WorkPackageRasciSync
     */
    private $workPackageRasciSync;

    /**
     * @var RepositoryInterface
     */
    private $workPackageRepository;

    private $em;

    protected function configure()
    {
        $this
            ->setName('app:workpackages-rasci-sync')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        $workPackages = $em
            ->getRepository(WorkPackage::class)
            ->getAllTasks()
        ;
        if (count($workPackages)) {
            $output->writeln('<info>WorkPackages-Rasci Sync Start</info>');
            $eventDispatcher = $this->getEventDispatcher();
            foreach ($workPackages as $wp) {
                $eventDispatcher->dispatch(WorkPackageEvents::POST_UPDATE, new WorkPackageEvent($wp));
            }
            $output->writeln('<info>WorkPackages-Rasci Sync Finish</info>');
        } else {
            $output->writeln('<info>There is no work packages</info>');
        }
    }

    /**
     * @return EventDispatcherInterface
     */
    protected function getEventDispatcher(): EventDispatcherInterface
    {
        return $this->getContainer()->get('event_dispatcher');
    }
}
