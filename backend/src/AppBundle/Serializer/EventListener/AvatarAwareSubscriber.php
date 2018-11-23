<?php

namespace AppBundle\Serializer\EventListener;

use AppBundle\Entity\User;
use Component\Resource\Model\BlameableInterface;
use Component\Resource\Model\ResponsibilityAwareInterface;
use Component\User\Model\UserAwareInterface;
use Component\User\Model\UserInterface;
use Component\Avatar\AvatarUrlResolverInterface;
use JMS\Serializer\EventDispatcher\Events;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\GenericSerializationVisitor;

class AvatarAwareSubscriber implements EventSubscriberInterface
{
    /**
     * @var AvatarUrlResolverInterface
     */
    private $avatarUrlResolver;

    /**
     * UserSubscriber constructor.
     *
     * @param AvatarUrlResolverInterface $avatarUrlResolver
     */
    public function __construct(AvatarUrlResolverInterface $avatarUrlResolver)
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
                'event' => Events::POST_SERIALIZE,
                'method' => 'onPostSerialize',
                'direction' => 'serialization',
            ],
        ];
    }

    /**
     * @param ObjectEvent $event
     */
    public function onPostSerialize(ObjectEvent $event)
    {
        /** @var GenericSerializationVisitor $visitor */
        $visitor = $event->getVisitor();

        /** @var UserInterface $user */
        $object = $event->getObject();
        if ($object instanceof UserInterface) {
            $this->addAvatar($visitor, $object);
        }

        if ($object instanceof UserAwareInterface) {
            $this->addUserAvatar($visitor, $object);
        }

        if ($object instanceof ResponsibilityAwareInterface) {
            $this->addResponsibilityAvatar($visitor, $object);
        }

        if ($object instanceof BlameableInterface) {
            $this->addBlamerAvatar($visitor, $object);
        }
    }

    /**
     * @param GenericSerializationVisitor $visitor
     * @param UserInterface               $user
     */
    private function addAvatar(GenericSerializationVisitor $visitor, UserInterface $user)
    {
        $url = $this->avatarUrlResolver->resolve($user);

        $visitor->setData('avatarUrl', $url);
    }

    /**
     * @param GenericSerializationVisitor $visitor
     * @param UserAwareInterface          $object
     */
    private function addUserAvatar(GenericSerializationVisitor $visitor, UserAwareInterface $object)
    {
        $user = $object->getUser();
        if (!$user) {
            return;
        }

        $url = $this->avatarUrlResolver->resolve($user);

        $visitor->setData('userAvatarUrl', $url);
    }

    /**
     * @param GenericSerializationVisitor  $visitor
     * @param ResponsibilityAwareInterface $object
     */
    private function addResponsibilityAvatar(GenericSerializationVisitor $visitor, ResponsibilityAwareInterface $object)
    {
        $user = $object->getResponsibility();
        if (!$user) {
            return;
        }

        $url = $this->avatarUrlResolver->resolve($user);

        $visitor->setData('responsibilityAvatarUrl', $url);
    }

    /**
     * @param GenericSerializationVisitor $visitor
     * @param BlameableInterface          $object
     */
    private function addBlamerAvatar(GenericSerializationVisitor $visitor, BlameableInterface $object)
    {
        $user = $object->getCreatedBy();
        if ($user) {
            $url = $this->avatarUrlResolver->resolve($user);

            $visitor->setData('createdByAvatarUrl', $url);
        }

        $user = $object->getUpdatedBy();
        if ($user) {
            $url = $this->avatarUrlResolver->resolve($user);

            $visitor->setData('updatedByAvatarUrl', $url);
        }
    }
}
