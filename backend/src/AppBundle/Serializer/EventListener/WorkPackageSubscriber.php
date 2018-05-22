<?php

namespace AppBundle\Serializer\EventListener;

use AppBundle\Entity\WorkPackage;
use Component\WorkPackage\Calculator\DateRangeCalculatorInterface;
use Component\WorkPackage\Calculator\WorkPackageProgressCalculatorInterface;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\GenericSerializationVisitor;

class WorkPackageSubscriber implements EventSubscriberInterface
{
    /**
     * @var WorkPackageProgressCalculatorInterface
     */
    private $workPackageProgressCalculator;

    /**
     * @var DateRangeCalculatorInterface
     */
    private $phaseForecastDatesCalculator;

    /**
     * @var DateRangeCalculatorInterface
     */
    private $phaseActualDatesCalculator;

    /**
     * WorkPackageSubscriber constructor.
     *
     * @param WorkPackageProgressCalculatorInterface $workPackageProgressCalculator
     * @param DateRangeCalculatorInterface           $phaseForecastDatesCalculator
     * @param DateRangeCalculatorInterface           $phaseActualDatesCalculator
     */
    public function __construct(
        WorkPackageProgressCalculatorInterface $workPackageProgressCalculator,
        DateRangeCalculatorInterface $phaseForecastDatesCalculator,
        DateRangeCalculatorInterface $phaseActualDatesCalculator
    ) {
        $this->workPackageProgressCalculator = $workPackageProgressCalculator;
        $this->phaseForecastDatesCalculator = $phaseForecastDatesCalculator;
        $this->phaseActualDatesCalculator = $phaseActualDatesCalculator;
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
                'class' => WorkPackage::class,
            ],
        ];
    }

    /**
     * @param ObjectEvent $event
     */
    public function onPostSerialize(ObjectEvent $event)
    {
        $workPackage = $event->getObject();

        /** @var GenericSerializationVisitor $visitor */
        $visitor = $event->getVisitor();

        $this->addProgress($visitor, $workPackage);
        $this->addForecastDates($visitor, $workPackage);
        $this->addActualDates($visitor, $workPackage);
    }

    /**
     * @param GenericSerializationVisitor $visitor
     * @param WorkPackage                 $workPackage
     */
    private function addProgress(GenericSerializationVisitor $visitor, WorkPackage $workPackage)
    {
        $visitor->setData('progress', $this->workPackageProgressCalculator->calculate($workPackage));
    }

    /**
     * @param GenericSerializationVisitor $visitor
     * @param WorkPackage                 $workPackage
     */
    private function addForecastDates(GenericSerializationVisitor $visitor, WorkPackage $workPackage)
    {
        if (!$workPackage->isPhase() && !$workPackage->isMilestone()) {
            return;
        }

        $range = $this->phaseForecastDatesCalculator->calculate($workPackage);

        $visitor->setData('forecastStartAt', $this->dateToString($range->getStart()));
        $visitor->setData('forecastFinishAt', $this->dateToString($range->getFinish()));
        $visitor->setData('forecastDurationDays', $range->getDurationDays());
    }

    /**
     * @param GenericSerializationVisitor $visitor
     * @param WorkPackage                 $workPackage
     */
    private function addActualDates(GenericSerializationVisitor $visitor, WorkPackage $workPackage)
    {
        if (!$workPackage->isPhase() && !$workPackage->isMilestone()) {
            return;
        }

        $range = $this->phaseActualDatesCalculator->calculate($workPackage);

        $visitor->setData('actualStartAt', $this->dateToString($range->getStart()));
        $visitor->setData('actualFinishAt', $this->dateToString($range->getFinish()));
        $visitor->setData('actualDurationDays', $range->getDurationDays());
    }

    /**
     * @param \DateTime|null $date
     *
     * @return null|string
     */
    private function dateToString(\DateTime $date = null)
    {
        if (!$date) {
            return null;
        }

        return $date->format('Y-m-d');
    }
}
