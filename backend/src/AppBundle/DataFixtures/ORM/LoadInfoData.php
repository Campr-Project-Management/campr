<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Info;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Insert database entries for Note entity.
 */
class LoadInfoData extends AbstractFixture implements OrderedFixtureInterface
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
            $info = (new Info())
                ->setTopic('note'.$i)
                ->setDescription('description'.$i)
                ->setDueDate($dueDate)
                ->setProject($project)
                ->setMeeting($meeting)
                ->setInfoStatus($this->getReference('infoStatus'.$i))
                ->setInfoCategory($this->getReference('infoCategory'.$i))
                ->setResponsibility($responsible)
            ;

            $manager->persist($info);
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
