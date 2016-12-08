<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\WorkPackageProjectWorkCostType;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Insert database entries for WorkPackageProjectWorkCostType entity.
 */
class LoadWorkPackageProjectWorkCostTypeData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 2; ++$i) {
            $workPackage = $this->getReference('work-package'.$i);
            $projectWorkCostType = $this->getReference('project-work-cost-type'.$i);

            $wppwct = (new WorkPackageProjectWorkCostType())
                ->setName('work-package-project-work-cost-type'.$i)
                ->setWorkPackage($workPackage)
                ->setProjectWorkCostType($projectWorkCostType)
            ;
            $this->setReference('work-package-project-work-cost-type'.$i, $wppwct);
            $manager->persist($wppwct);
        }

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 3;
    }
}
