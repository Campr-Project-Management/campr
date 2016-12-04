<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\ChatRoom;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadChatRoomData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $project = $this->getReference('project1');

        for ($i = 1; $i <= 2; ++$i) {
            $chatRoom = (new ChatRoom())
                ->setName('chat'.$i)
                ->setProject($project)
            ;
            $this->setReference('chat'.$i, $chatRoom);
            $manager->persist($chatRoom);
        }

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 8;
    }
}
