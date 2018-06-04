<?php

namespace AppBundle\Serializer\EventListener;

use AppBundle\Entity\Info;
use Component\User\UserAvatarUrlResolverInterface;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\GenericSerializationVisitor;

class InfoSubscriber implements EventSubscriberInterface
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
                'class' => Info::class,
            ],
        ];
    }

    /**
     * @param ObjectEvent $event
     */
    public function onPostSerialize(ObjectEvent $event)
    {
        /** @var Info $info */
        $info = $event->getObject();

        /** @var GenericSerializationVisitor $visitor */
        $visitor = $event->getVisitor();

        $this->addReponsibilityAvatar($visitor, $info);
    }

    /**
     * @param GenericSerializationVisitor $visitor
     * @param Info                        $info
     */
    private function addReponsibilityAvatar(GenericSerializationVisitor $visitor, Info $info)
    {
        $responsibility = $info->getResponsibility();
        if (!$responsibility) {
            return;
        }

        $visitor->setData('responsibilityAvatarUrl', $this->avatarUrlResolver->resolve($responsibility));
    }
}
