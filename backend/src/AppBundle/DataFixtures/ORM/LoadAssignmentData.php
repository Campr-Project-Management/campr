<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Assignment;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Insert database entries for Assignment entity.
 */
class LoadAssignmentData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $startedAt = new \DateTime('2016-12-12');
        $finishedAt = new \DateTime('2017-01-01');
        $workPackage = $this->getReference('work-package2');

        for ($i = 1; $i <= 2; ++$i) {
            $wppwct = $this->getReference('work-package-project-work-cost-type'.$i);

            $assignment = (new Assignment())
                ->setWorkPackage($workPackage)
                ->setWorkPackageProjectWorkCostType($wppwct)
                ->setMilestone($i * $i)
                ->setStartedAt($startedAt)
                ->setFinishedAt($finishedAt)
            ;
            $this->setReference('assignment'.$i, $assignment);
            $manager->persist($assignment);
        }

        $assignment = (new Assignment())
            ->setWorkPackage($this->getReference('work-package1'))
            ->setMilestone(2)
            ->setStartedAt(new \DateTime('2017-01-01'))
            ->setFinishedAt(new \DateTime('2017-01-04'))
        ;
        $this->setReference('assignment3', $assignment);
        $manager->persist($assignment);

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 4;
    }
}
