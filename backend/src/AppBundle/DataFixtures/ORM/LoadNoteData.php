<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Note;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Insert database entries for Note entity.
 */
class LoadNoteData extends AbstractFixture implements OrderedFixtureInterface
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
        $date = new \DateTime('2017-01-01');
        $dueDate = new \DateTime('2017-05-01');

        for ($i = 1; $i <= 2; ++$i) {
            $note = (new Note())
                ->setTitle('note'.$i)
                ->setDescription('description'.$i)
                ->setDate($date)
                ->setDueDate($dueDate)
                ->setProject($project)
                ->setMeeting($meeting)
                ->setResponsibility($responsible)
                ->setStatus($status)
            ;

            $manager->persist($note);
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
