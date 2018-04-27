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
                ->setPuid($i)
                ->setName('work-package'.$i)
                ->setType(WorkPackage::TYPE_TASK)
                ->setContent('content'.$i)
                ->setScheduledStartAt($startAt)
                ->setScheduledFinishAt($finishAt)
                ->setForecastStartAt($startAt)
                ->setForecastFinishAt($finishAt)
                ->setResponsibility($user5)
                ->setColorStatus($colorStatus)
                ->setProject($project)
                ->setDuration(0)
            ;
            $this->setReference('work-package'.$i, $workPackage);
            $manager->persist($workPackage);
            $manager->flush();
        }

        $workPackage = (new WorkPackage())
            ->setPuid($i)
            ->setName('work-package3')
            ->setType(WorkPackage::TYPE_MILESTONE)
            ->setContent('content')
            ->setScheduledStartAt(new \DateTime('2017-01-01'))
            ->setScheduledFinishAt(new \DateTime('2017-01-05'))
            ->setForecastStartAt(new \DateTime('2017-01-01'))
            ->setForecastFinishAt(new \DateTime('2017-01-05'))
            ->setResponsibility($user4)
            ->setColorStatus($colorStatus)
            ->setProject($project)
            ->setDuration(0)
        ;
        $manager->persist($workPackage);
        $manager->flush();

        $workPackage = (new WorkPackage())
            ->setPuid($i + 1)
            ->setName('work-package4')
            ->setType(WorkPackage::TYPE_PHASE)
            ->setContent('content4')
            ->setScheduledStartAt(new \DateTime('2017-01-01'))
            ->setScheduledFinishAt(new \DateTime('2017-01-05'))
            ->setForecastStartAt(new \DateTime('2017-01-01'))
            ->setForecastFinishAt(new \DateTime('2017-01-05'))
            ->setResponsibility($user4)
            ->setColorStatus($colorStatus)
            ->setProject($project)
            ->setDuration(0)
        ;
        $manager->persist($workPackage);
        $manager->flush();

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
