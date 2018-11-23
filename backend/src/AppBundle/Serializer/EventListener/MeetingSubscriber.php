<?php

namespace AppBundle\Serializer\EventListener;

use AppBundle\Entity\Decision;
use AppBundle\Entity\Info;
use AppBundle\Entity\Meeting;
use AppBundle\Entity\Todo;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\Context;
use JMS\Serializer\EventDispatcher\Events;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\GenericSerializationVisitor;

class MeetingSubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * MeetingSubscriber constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            [
                'event' => Events::POST_SERIALIZE,
                'method' => 'onPostSerialize',
                'class' => Meeting::class,
            ],
        ];
    }

    /**
     * @param ObjectEvent $event
     */
    public function onPostSerialize(ObjectEvent $event)
    {
        /** @var Meeting $meeting */
        $meeting = $event->getObject();

        /** @var GenericSerializationVisitor $visitor */
        $visitor = $event->getVisitor();

        /** @var Context $context */
        $context = $event->getContext();

        $this->addOpenDecisions($visitor, $context, $meeting);
        $this->addOpenTodos($visitor, $context, $meeting);
        $this->addOpenInfos($visitor, $context, $meeting);
    }

    public function addOpenDecisions(GenericSerializationVisitor $visitor, Context $context, Meeting $meeting)
    {
        $decisions = $this
            ->em
            ->getRepository(Decision::class)
            ->findOpenAndNotExpiredByMeeting($meeting)
        ;

        $visitor->setData('openDecisions', $visitor->visitArray($decisions, [Meeting::class], $context));
    }

    public function addOpenTodos(GenericSerializationVisitor $visitor, Context $context, Meeting $meeting)
    {
        $todos = $this
            ->em
            ->getRepository(Todo::class)
            ->findOpenAndNotExpiredByMeeting($meeting)
        ;

        $visitor->setData('openTodos', $visitor->visitArray($todos, [Todo::class], $context));
    }

    public function addOpenInfos(GenericSerializationVisitor $visitor, Context $context, Meeting $meeting)
    {
        $infos = $this
            ->em
            ->getRepository(Info::class)
            ->findOpenAndNotExpiredByMeeting($meeting)
        ;

        $visitor->setData('openInfos', $visitor->visitArray($infos, [Info::class], $context));
    }
}
