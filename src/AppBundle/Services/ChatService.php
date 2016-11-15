<?php

namespace AppBundle\Services;

use AppBundle\Entity\ChatRoom;
use AppBundle\Entity\Message;
use AppBundle\Entity\Project;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;

class ChatService
{
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function createProjectChatRoom(Project $project, $name)
    {
        $chatRoom = new ChatRoom();
        $chatRoom->setName($name);
        $chatRoom->setProject($project);

        $this->em->persist($chatRoom);
        $this->em->flush();

        return $chatRoom;
    }

    public function createMessage(Project $project, $messageBody, User $from, ChatRoom $chatRoom = null, User $to = null)
    {
        $message = new Message();
        $message->setProject($project);
        $message->setFrom($from);
        if ($chatRoom) {
            $message->setChatRoom($chatRoom);
        }
        if ($to) {
            $message->setTo($to);
        }
        $message->setBody($messageBody);
        $this->em->persist($message);
        $this->em->flush();

        return $message;
    }

    public function getProjectChatList(Project $project, User $user)
    {
        $list = [];
        foreach ($project->getChatRooms() as $room) {
            $item['id'] = $room->getId();
            $item['name'] = $room->getName();
            if ($message = $room->getLastMessage()) {
                $item['message'] = $message->getBody();
                $item['time'] = $message->getCreatedAt();
            }
            $list[] = $item;
        }

        $privateMessages = $this
            ->em
            ->getRepository(Message::class)
            ->findPrivateByUserAndProject($user, $project)
        ;
        foreach ($privateMessages as $pm) {
            if (($pm->getFrom() === $user && $pm->getDeletedFromAt())
                || ($pm->getTo() === $user && $pm->getDeletedToAt())
            ) {
                continue;
            }
            $from = $pm->getFrom() === $user ? $pm->getTo() : $pm->getFrom();
            $item['id'] = $from->getId();
            $item['name'] = '@'.$from->getUsername();
            $item['message'] = $pm->getBody();
            $item['time'] = $pm->getCreatedAt();
            $item['chat_key'] = $pm->getChatKey();
            $list[] = $item;
        }

        usort($list, function ($message, $nextMessage) {
            return strtotime($message['time']->format('Y/m/d H:i:s')) < strtotime($nextMessage['time']->format('Y/m/d H:i:s')) ? 1 : -1;
        });

        return $list;
    }
}
