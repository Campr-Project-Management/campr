<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Decision;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Insert database entries for Decision entity.
 */
class LoadDecisionData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $project = $this->getReference('project1');
        $meeting = $this->getReference('meeting1');
        $responsible = $this->getReference('user4');
        $status = $this->getReference('status1');
        $date = new \DateTime();

        for ($i = 1; $i <= 2; ++$i) {
            $dueDate = new \DateTime(sprintf('+%d days', $i * 2));

            $decision = (new Decision())
                ->setTitle('decision'.$i)
                ->setDescription('description'.$i)
                ->setShowInStatusReport(false)
                ->setDate($date)
                ->setDueDate($dueDate)
                ->setProject($project)
                ->setMeeting($meeting)
                ->setStatus($status)
                ->setResponsibility($responsible)
            ;

            $manager->persist($decision);
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
