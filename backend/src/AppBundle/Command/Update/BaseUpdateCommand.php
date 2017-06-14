<?php

namespace AppBundle\Command\Update;

use AppBundle\Entity\Project;
use AppBundle\Entity\WorkPackage;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
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
        array $parent = null
    ) {
        $qb = $this
            ->getEm()
            ->getRepository(WorkPackage::class)
            ->createQueryBuilder('wp')
            ->select('wp')
        ;

        $where = 'wp.project = :project';
        $params = [
            'project' => $project->getId(),
        ];

        if ($parent) {
            $where .= ' AND (wp.phase = :parent OR wp.milestone = :parent OR wp.parent = :parent)';
            $params['parent'] = $parent['id'];
        } else {
            $where .= ' AND wp.type = :type AND wp.parent IS NULL';
            $params['type'] = WorkPackage::TYPE_PHASE;
        }

        return $qb
            ->where($where)
            ->setParameters($params)
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY)
        ;
    }
}
