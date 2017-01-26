<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\WorkPackage;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Insert database entries for WorkPackage entity.
 */
class LoadWorkPackageData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $user5 = $this->getReference('user5');
        $user4 = $this->getReference('user4');
        $project = $this->getReference('project1');
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
                ->setResponsibility($user5)
                ->setColorStatus($colorStatus)
                ->setProject($project)
            ;
            $this->setReference('work-package'.$i, $workPackage);
            $manager->persist($workPackage);
        }

        $workPackage = (new WorkPackage())
            ->setPuid('1234')
            ->setName('work-package3')
            ->setContent('content')
            ->setScheduledStartAt(new \DateTime('2017-01-01'))
            ->setScheduledFinishAt(new \DateTime('2017-01-05'))
            ->setResponsibility($user4)
            ->setColorStatus($colorStatus)
            ->setProject($project)
        ;
        $manager->persist($workPackage);
        $workPackage = (new WorkPackage())
            ->setPuid('123456')
            ->setName('work-package4')
            ->setContent('content4')
            ->setScheduledStartAt(new \DateTime('2017-01-01'))
            ->setScheduledFinishAt(new \DateTime('2017-01-05'))
            ->setResponsibility($user4)
            ->setColorStatus($colorStatus)
            ->setProject($project)
        ;
        $manager->persist($workPackage);

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
