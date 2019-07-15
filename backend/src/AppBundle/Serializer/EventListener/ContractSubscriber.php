<?php

declare(strict_types=1);

namespace AppBundle\Serializer\EventListener;

use AppBundle\Entity\Contract;
use Component\Snapshot\Transformer\OpportunitiesTransformer;
use Component\Snapshot\Transformer\RisksTransformer;
use JMS\Serializer\EventDispatcher\Events;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\JsonSerializationVisitor;

class ContractSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            [
                'event' => Events::POST_SERIALIZE,
                'method' => 'onPostSerialize',
                'class' => Contract::class,
                'format' => 'json',
            ],
        ];
    }

    /**
     * @var RisksTransformer
     */
    private $risksTransformer;

    /**
     * @var OpportunitiesTransformer
     */
    private $opportunitiesTransformer;

    /**
     * ContractSubscriber constructor.
     *
     * @param RisksTransformer         $risksTransformer
     * @param OpportunitiesTransformer $opportunitiesTransformer
     */
    public function __construct(
        RisksTransformer $risksTransformer,
        OpportunitiesTransformer $opportunitiesTransformer
    ) {
        $this->risksTransformer = $risksTransformer;
        $this->opportunitiesTransformer = $opportunitiesTransformer;
    }

    /**
     * @param ObjectEvent $event
     */
    public function onPostSerialize(ObjectEvent $event): void
    {
        /** @var Contract $contract */
        $contract = $event->getObject();

        /** @var JsonSerializationVisitor $visitor */
        $visitor = $event->getVisitor();

        if (!($contract instanceof Contract) || !$contract->getProject()) {
            return;
        }

        $visitor->setData('risks', $this->risksTransformer->transform($contract->getProject()));
        $visitor->setData('opportunities', $this->opportunitiesTransformer->transform($contract->getProject()));
    }
}
