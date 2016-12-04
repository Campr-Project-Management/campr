<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Message;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadMessageData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $project = $this->getReference('project1');
        $chatRoom = $this->getReference('chat1');
        $from = $this->getReference('superadmin');
        $to = $this->getReference('user5');
        for ($i = 1; $i <= 3; ++$i) {
            $message = (new Message())
                ->setProject($project)
                ->setChatRoom($chatRoom)
                ->setFrom($from)
                ->setBody('message'.$i)
            ;
            $this->setReference('message'.$i, $message);
            $manager->persist($message);
        }

        for ($i = 4; $i <= 7; ++$i) {
            $message = (new Message())
                ->setProject($project)
                ->setFrom($from)
                ->setTo($to)
                ->setBody('private_message'.$i)
            ;
            $this->setReference('private'.$i, $message);
            $manager->persist($message);
        }

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 9;
    }
}
