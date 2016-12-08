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
        $startedAt = new \DateTime();
        $finishedAt = new \DateTime('+5 days');

        for ($i = 1; $i <= 2; ++$i) {
            $workPackage = $this->getReference('work-package'.$i);
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

            $finishedAt->add(new \DateInterval('P5D'));
        }

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
