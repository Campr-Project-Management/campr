<?php

namespace AppBundle\Serializer\EventListener;

use AppBundle\Entity\User;
use Component\User\UserAvatarUrlResolverInterface;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\GenericSerializationVisitor;

class UserSubscriber implements EventSubscriberInterface
{
    /**
     * @var UserAvatarUrlResolverInterface
     */
    private $avatarUrlResolver;

    /**
     * @var UserAvatarUrlResolverInterface
     */
    private $uploadedAvatarUrlResolver;

    /**
     * UserSubscriber constructor.
     *
     * @param UserAvatarUrlResolverInterface $avatarUrlResolver
     * @param UserAvatarUrlResolverInterface $uploadedAvatarUrlResolver
     */
    public function __construct(
        UserAvatarUrlResolverInterface $avatarUrlResolver,
        UserAvatarUrlResolverInterface $uploadedAvatarUrlResolver
    ) {
        $this->avatarUrlResolver = $avatarUrlResolver;
        $this->uploadedAvatarUrlResolver = $uploadedAvatarUrlResolver;
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
                'class' => User::class,
            ],
        ];
    }

    /**
     * @param ObjectEvent $event
     */
    public function onPostSerialize(ObjectEvent $event)
    {
        /** @var User $user */
        $user = $event->getObject();

        /** @var GenericSerializationVisitor $visitor */
        $visitor = $event->getVisitor();

        $this->addAvatar($visitor, $user);
    }

    /**
     * @param GenericSerializationVisitor $visitor
     * @param User                        $user
     */
    private function addAvatar(GenericSerializationVisitor $visitor, User $user)
    {
        $visitor->setData('avatarUrl', $this->avatarUrlResolver->resolve($user));
        $visitor->setData('avatar', $this->uploadedAvatarUrlResolver->resolve($user));
    }
}
