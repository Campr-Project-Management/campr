<?php

namespace AppBundle\Serializer\EventListener;

use AppBundle\Entity\WorkPackage;
use Component\WorkPackage\Calculator\DateRangeCalculatorInterface;
use Component\WorkPackage\Calculator\StatusCalculatorInterface;
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
     * @var DateRangeCalculatorInterface
     */
    private $milestoneForecastDatesCalculator;

    /**
     * @var DateRangeCalculatorInterface
     */
    private $milestoneActualDatesCalculator;

    /**
     * @var StatusCalculatorInterface
     */
    private $phaseStatusCalculator;

    /**
     * @var StatusCalculatorInterface
     */
    private $milestoneStatusCalculator;

    /**
     * WorkPackageSubscriber constructor.
     *
     * @param WorkPackageProgressCalculatorInterface $workPackageProgressCalculator
     * @param DateRangeCalculatorInterface           $phaseForecastDatesCalculator
     * @param DateRangeCalculatorInterface           $phaseActualDatesCalculator
     * @param DateRangeCalculatorInterface           $milestoneForecastDatesCalculator
     * @param DateRangeCalculatorInterface           $milestoneActualDatesCalculator
     * @param StatusCalculatorInterface              $phaseStatusCalculator
     * @param StatusCalculatorInterface              $milestoneStatusCalculator
     */
    public function __construct(
        WorkPackageProgressCalculatorInterface $workPackageProgressCalculator,
        DateRangeCalculatorInterface $phaseForecastDatesCalculator,
        DateRangeCalculatorInterface $phaseActualDatesCalculator,
        DateRangeCalculatorInterface $milestoneForecastDatesCalculator,
        DateRangeCalculatorInterface $milestoneActualDatesCalculator,
        StatusCalculatorInterface $phaseStatusCalculator,
        StatusCalculatorInterface $milestoneStatusCalculator
    ) {
        $this->workPackageProgressCalculator = $workPackageProgressCalculator;
        $this->phaseForecastDatesCalculator = $phaseForecastDatesCalculator;
        $this->phaseActualDatesCalculator = $phaseActualDatesCalculator;
        $this->milestoneForecastDatesCalculator = $milestoneForecastDatesCalculator;
        $this->milestoneActualDatesCalculator = $milestoneActualDatesCalculator;
        $this->phaseStatusCalculator = $phaseStatusCalculator;
        $this->milestoneStatusCalculator = $milestoneStatusCalculator;
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
        $this->addWorkPackageStatus($visitor, $workPackage);
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

        $calculator = $this->phaseForecastDatesCalculator;
        if ($workPackage->isMilestone()) {
            $calculator = $this->milestoneForecastDatesCalculator;
        }

        $range = $calculator->calculate($workPackage);

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

        $calculator = $this->phaseActualDatesCalculator;
        if ($workPackage->isMilestone()) {
            $calculator = $this->milestoneActualDatesCalculator;
        }

        $range = $calculator->calculate($workPackage);

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

    /**
     * @param GenericSerializationVisitor $visitor
     * @param WorkPackage                 $workPackage
     */
    private function addWorkPackageStatus(GenericSerializationVisitor $visitor, WorkPackage $workPackage)
    {
        if (!$workPackage->isPhase() && !$workPackage->isMilestone()) {
            return;
        }

        $calculator = $this->phaseStatusCalculator;
        if ($workPackage->isMilestone()) {
            $calculator = $this->milestoneStatusCalculator;
        }

        $status = $calculator->calculate($workPackage);

        $visitor->setData('workPackageStatusId', $status->getId());
        $visitor->setData('workPackageStatus', $status->getId());
        $visitor->setData('workPackageStatusName', $status->getName());
        $visitor->setData('workPackageStatusCode', $status->getCode());
    }
}
