<?php

namespace AppBundle\Serializer\EventListener;

use AppBundle\Entity\Decision;
use Component\User\UserAvatarUrlResolverInterface;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\GenericSerializationVisitor;

class DecisionSubscriber implements EventSubscriberInterface
{
    /**
     * @var UserAvatarUrlResolverInterface
     */
    private $avatarUrlResolver;

    /**
     * InfoSubscriber constructor.
     *
     * @param UserAvatarUrlResolverInterface $avatarUrlResolver
     */
    public function __construct(UserAvatarUrlResolverInterface $avatarUrlResolver)
    {
        $this->avatarUrlResolver = $avatarUrlResolver;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            [
                'event' => 'serializer.post_serialize',
                'method' => 'onPostSerialize',
                'class' => Decision::class,
            ],
        ];
    }

    /**
     * @param ObjectEvent $event
     */
    public function onPostSerialize(ObjectEvent $event)
    {
        /** @var Decision $decision */
        $decision = $event->getObject();

        /** @var GenericSerializationVisitor $visitor */
        $visitor = $event->getVisitor();

        $this->addReponsibilityAvatar($visitor, $decision);
    }

    /**
     * @param GenericSerializationVisitor $visitor
     * @param Decision                    $decision
     */
    private function addReponsibilityAvatar(GenericSerializationVisitor $visitor, Decision $decision)
    {
        $responsibility = $decision->getResponsibility();
        if (!$responsibility) {
            return;
        }

        $visitor->setData('responsibilityAvatarUrl', $this->avatarUrlResolver->resolve($responsibility));
    }
}
