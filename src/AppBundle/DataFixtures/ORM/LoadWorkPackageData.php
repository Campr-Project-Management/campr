<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\WorkPackage;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadWorkPackageData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $responsible = $this->getReference('user2');
        $startAt = new \DateTime();

        for ($i = 1; $i <= 2; ++$i) {
            $colorStatus = $this->getReference('color-status'.$i);
            $finishAt = new \DateTime(sprintf('+%d days', $i * 2));

            $workPackage = (new WorkPackage())
                ->setPuid('puid'.$i)
                ->setName('work-package'.$i)
                ->setContent('content'.$i)
                ->setScheduledStartAt($startAt)
                ->setScheduledFinishAt($finishAt)
                ->setResponsibility($responsible)
                ->setColorStatus($colorStatus)
            ;
            $this->setReference('work-package'.$i, $workPackage);
            $manager->persist($workPackage);
        }

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 2;
    }
}
