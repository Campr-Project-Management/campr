<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Todo;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Insert database entries for Todo entity.
 */
class LoadTodoData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $project = $this->getReference('project1');
        $meeting = $this->getReference('meeting1');
        $responsible = $this->getReference('user4');
        $date = new \DateTime('2017-01-01');
        $dueDate = new \DateTime('2017-05-01');

        for ($i = 1; $i <= 2; ++$i) {
            $todo = (new Todo())
                ->setTitle('todo'.$i)
                ->setProject($project)
                ->setMeeting($meeting)
                ->setDescription('description for todo'.$i)
                ->setResponsibility($responsible)
                ->setDueDate($dueDate)
            ;
            $manager->persist($todo);
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
