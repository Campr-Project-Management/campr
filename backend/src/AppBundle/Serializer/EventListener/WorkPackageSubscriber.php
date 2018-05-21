<?php

namespace AppBundle\Serializer\EventListener;

use AppBundle\Entity\WorkPackage;
use Component\WorkPackage\Calculator\WorkPackageProgressCalculatorInterface;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\GenericSerializationVisitor;

class WorkPackageSubscriber implements EventSubscriberInterface
{
    private $workPackageProgressCalculator;

    public function __construct(WorkPackageProgressCalculatorInterface $workPackageProgressCalculator)
    {
        $this->workPackageProgressCalculator = $workPackageProgressCalculator;
    }

    public static function getSubscribedEvents()
    {
        return [
            [
                'event' => 'serializer.post_serialize',
                'method' => 'onPostSerialize',
                'class' => WorkPackage::class,
            ],
        ];
    }

    public function onPostSerialize(ObjectEvent $event)
    {
        $workPackage = $event->getObject();

        $visitor = $event->getVisitor();

        $this->addProgress($visitor, $workPackage);
    }

    private function addProgress(GenericSerializationVisitor $visitor, WorkPackage $workPackage)
    {
        $visitor->setData('progress', $this->workPackageProgressCalculator->calculate($workPackage));
    }
}
