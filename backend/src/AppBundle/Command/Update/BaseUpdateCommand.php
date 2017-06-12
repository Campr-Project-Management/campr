<?php

namespace AppBundle\Command\Update;

use AppBundle\Entity\Project;
use AppBundle\Entity\WorkPackage;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class BaseUpdateCommand extends ContainerAwareCommand
{
    const TYPE_NESTING = [
        WorkPackage::TYPE_PHASE => [WorkPackage::TYPE_PHASE, WorkPackage::TYPE_MILESTONE, WorkPackage::TYPE_TASK],
        WorkPackage::TYPE_MILESTONE => [WorkPackage::TYPE_MILESTONE, WorkPackage::TYPE_TASK],
        WorkPackage::TYPE_TASK => [WorkPackage::TYPE_TASK],
    ];

    /** @var OutputInterface */
    protected $output;

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * @return EntityManager
     */
    protected function getEm()
    {
        return $this->getContainer()->get('doctrine.orm.default_entity_manager');
    }

    protected function getWorkPackages(
        Project $project,
        array $parent = null,
        $includeDependants = false,
        $includeChildren = true
    ) {
        $qb = $this
            ->getEm()
            ->getRepository(WorkPackage::class)
            ->createQueryBuilder('wp')
        ;
        $where = 'wp.project = :project AND wp.type IN (:types)';
        $params = [
            'project' => $project->getId(),
        ];
        if ($parent) {
            switch ($parent['type']) {
                case WorkPackage::TYPE_TASK:
                    $wherePiece = ' AND (wp.parent = :parent %s)';
                    break;
                case WorkPackage::TYPE_MILESTONE:
                    $wherePiece = ' AND ((wp.milestone = :parent OR wp.parent = :parent) %s)';
                    break;
                case WorkPackage::TYPE_PHASE:
                default: // in case 0 fails
                    $wherePiece = ' AND ((wp.phase = :parent OR wp.parent = :parent) %s)';
                    break;
            }
            if ($includeDependants) {
                $qb->leftJoin('wp.dependants', 'dependants');
            }
            $where .= sprintf(
                $wherePiece,
                $includeDependants
                    ? ' OR dependants.id = :parent'
                    : ''
            );
            $params['parent'] = $parent['id'];
            $params['types'] = self::TYPE_NESTING[$parent['type']];
        } else {
            $params['types'] = [WorkPackage::TYPE_PHASE];
        }
        $workPackages = $qb
            ->select('wp')
            ->where($where) // AND wp.type = :type
            ->setParameters($params)
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY)
        ;

        if ($includeChildren) {
            foreach ($workPackages as $key => $workPackage) {
                $workPackages[$key]['children'] = $this->getWorkPackages(
                    $project,
                    $workPackage,
                    $includeDependants,
                    $includeChildren
                );
            }
        }

        return $workPackages;
    }
}
