<?php

namespace AppBundle\Services;

use AppBundle\Entity\ChatRoom;
use AppBundle\Entity\Message;
use AppBundle\Entity\Project;
use Doctrine\ORM\EntityManager;

class ChatService
{
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getProjectOrderedChats(Project $project)
    {
        $chats = [];
//        $chatRooms = $this
//            ->em
//            ->getRepository(ChatRoom::class)
//            ->findByProjectOrderedByMessage($project)
//        ;

        $privateMessages = $this
            ->em
            ->getRepository(Message::class)
            ->findBy(['chatRoom' => null, 'project' => $project], ['createdAt' => 'DESC'])
        ;

        return $chats;
    }
}
