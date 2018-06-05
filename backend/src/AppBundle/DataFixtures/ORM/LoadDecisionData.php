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
        $dueDate = new \DateTime('2017-05-01');

        for ($i = 1; $i <= 2; ++$i) {
            $decision = (new Decision())
                ->setTitle('decision'.$i)
                ->setDescription('description'.$i)
                ->setShowInStatusReport(false)
                ->setDueDate($dueDate)
                ->setProject($project)
                ->setMeeting($meeting)
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
