<?php

namespace AppBundle\Command\Update;

use AppBundle\Entity\WorkPackage;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WorkPackageDatesCommand extends ContainerAwareCommand
{
    /** @var OutputInterface */
    private $output;

    protected function configure()
    {
        $this->setName('app:update:work-package-dates');
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * @return EntityManager
     */
    private function getEm()
    {
        return $this->getContainer()->get('doctrine.orm.default_entity_manager');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getEm();
        /** @var WorkPackage[] $workPackages */
        $workPackages = $em
            ->getRepository(WorkPackage::class)
            ->createQueryBuilder('wp')
            ->select('wp, dependants, dependencies')
            ->leftJoin('wp.dependants', 'dependants')
            ->leftJoin('wp.dependencies', 'dependencies')
            ->getQuery()
//            ->getResult()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY)
        ;

        $dependancies = [];
        // flatten everything!
        foreach ($workPackages as $workPackage) {
            //            print_r($workPackage);
//            echo $workPackage->getId(), ' deps: ', $workPackage->getDependants()->count(),
//                '/', $workPackage->getDependencies()->count(), PHP_EOL;
        }
    }
}
