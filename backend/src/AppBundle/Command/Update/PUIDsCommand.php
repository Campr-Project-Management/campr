<?php

namespace AppBundle\Command\Update;

use AppBundle\Entity\Project;
use AppBundle\Entity\WorkPackage;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PUIDsCommand extends ContainerAwareCommand
{
    /** @var OutputInterface */
    private $output;

    /** @var EntityManager */
    private $em;

    protected function configure()
    {
        $this->setName('app:update:puids');
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $this->em = $this->getContainer()->get('doctrine.orm.default_entity_manager');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $projects = $this->em->getRepository(Project::class)->findAll();
        if (!$projects || !count($projects)) {
            $output->writeln('<comment>No projects found.</comment>');

            return;
        }
        $this->em->clear();
        // reset all PUIDs before updating
        $this->em->beginTransaction();
        $this
            ->em
            ->createQuery(sprintf(
                'UPDATE %s AS wp SET wp.puid = CONCAT(wp.id, :rand)',
                WorkPackage::class
            ))
            ->execute([
                'rand' => random_int(0, time()),
            ])
        ;
        $this->em->commit();
        foreach ($projects as $project) {
            $output->writeln(sprintf(
                '<info>Processing: %s</info>',
                $project
            ));
            $this->updateWorkPackages($project->getId());
        }
    }

    private function updateWorkPackages(int $projectId, string $puidPrefix = '', int $workPackageParentId = null)
    {
        $wps = $this->findWorkPackages($projectId, $workPackageParentId);
        $c = 0;
        foreach ($wps as $wp) {
            ++$c;
            $dql = sprintf(
                'UPDATE %s wp SET wp.puid = :puid WHERE wp.id = :id',
                WorkPackage::class
            );
            $params = [
                'puid' => $puidPrefix ? $puidPrefix.'.'.$c : $c,
                'id' => $wp['id'],
            ];
            $this
                ->em
                ->createQuery($dql)
                ->execute($params)
            ;
            $this->updateWorkPackages($projectId, $c, (int) $wp['id']);
        }
    }

    private function findWorkPackages(int $projectId, int $workPackageId = null)
    {
        $dql = sprintf(
            '
                SELECT
                    wp.id,
                    wp.puid,
                    pwp.puid AS parent_puid
                FROM %1$s AS wp
                LEFT JOIN %1$s AS pwp WITH pwp.id = wp.parent
                WHERE wp.project = :project
                AND wp.parent %2$s',
            WorkPackage::class,
            $workPackageId
                ? '= :parent'
                : 'IS NULL'
        );
        $params = [
            'project' => $projectId,
        ];
        if ($workPackageId) {
            $params['parent'] = $workPackageId;
        }

        return $this
            ->em
            ->createQuery($dql)
            ->setParameters($params)
            ->getResult(Query::HYDRATE_ARRAY)
        ;
    }
}
