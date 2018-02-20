<?php

namespace AppBundle\Command;

use AppBundle\Entity\Project;
use AppBundle\Entity\WorkPackage;
use AppBundle\Entity\WorkPackageStatus;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('test')->addArgument('projectId', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        /** @var Project $project */
        $project = $em
            ->getRepository(Project::class)
            ->find($input->getArgument('projectId'))
        ;

        if (!$project) {
            return 1;
        }

        foreach ($project->getWorkPackages() as $workPackage) {
            $em->remove($workPackage);
            $project->removeWorkPackage($workPackage);

            $output->writeln(sprintf(
                'Removing WP: #%d',
                $workPackage->getId()
            ));
        }

        $em->persist($project);
        $em->flush();

        $tasks = $this->getTasksFromTranslation();
        foreach ($tasks as $task) {
            $wp = new WorkPackage();
            $wp->setProject($project);
            $wp->setResponsibility($project->getProjectUsers()->first()->getUser());
            $wp->setType(WorkPackage::TYPE_TUTORIAL);
            $wp->setName($task['title']);
            $wp->setContent($task['description']);
            $wp->setWorkPackageStatus(
                $em->getReference(WorkPackageStatus::class, WorkPackageStatus::OPEN)
            );

            $em->persist($wp);
        }

        $em->flush();
    }

    private function getTasksFromTranslation()
    {
        /** @var \Symfony\Component\Translation\MessageCatalogue $catalogue */
        $catalogue = $this->getContainer()->get('translator')->getCatalogue();
        $tasks = $catalogue->all('tasks');

        $keys = array_keys($tasks);

        $out = [];

        foreach ($keys as $key) {
            $parts = explode('.', $key);

            if (count($parts) !== 3) {
                continue;
            }

            if (!isset($out[$parts[1]])) {
                $out[$parts[1]] = [];
            }

            $out[$parts[1]][$parts[2]] = $tasks[$key];
        }

        return $out;
    }
}
