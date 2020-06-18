<?php

namespace AppBundle\Serializer\EventListener;

use AppBundle\Entity\Team;
use Component\Uploader\Resolver\UrlResolverInterface;
use JMS\Serializer\EventDispatcher\Events;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\GenericSerializationVisitor;
use JMS\Serializer\JsonSerializationVisitor;

class TeamSubscriber implements EventSubscriberInterface
{
    /**
     * @var UrlResolverInterface
     */
    private $logoUrlResolver;

    /**
     * WorkspaceSubscriber constructor.
     *
     * @param UrlResolverInterface $logoUrlResolver
     */
    public function __construct(UrlResolverInterface $logoUrlResolver)
    {
        $this->logoUrlResolver = $logoUrlResolver;
    }

    /**
     * @return array|array[]
     */
    public static function getSubscribedEvents()
    {
        return [
            [
                'event' => Events::POST_SERIALIZE,
                'method' => 'onPostSerialize',
                'class' => Team::class,
                'format' => 'json',
            ],
        ];
    }

    /**
     * @param ObjectEvent $event
     */
    public function onPostSerialize(ObjectEvent $event)
    {
        /** @var Team $team */
        $team = $event->getObject();

        /** @var JsonSerializationVisitor $visitor */
        $visitor = $event->getVisitor();

        $this->addLogoUrl($visitor, $team);
    }

    /**
     * @param GenericSerializationVisitor $visitor
     * @param Team                        $team
     */
    private function addLogoUrl(GenericSerializationVisitor $visitor, Team $team)
    {
        if (!empty($team->getLogoUrl())) {
            $logoUrl = $team->getLogoUrl();
        } else {
            $logoUrl = $this->logoUrlResolver->resolve($team);
        }

        $visitor->setData('logoUrl', $logoUrl);
    }
}
