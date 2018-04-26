<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\WorkPackage;
use AppBundle\Event\WorkPackageEvent;
use Component\WorkPackage\Calculator\DateRangeCalculatorInterface;
use Component\WorkPackage\WorkPackageEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RecalculatePhaseDatesSubscriber implements EventSubscriberInterface
{
    /**
     * @var DateRangeCalculatorInterface
     */
    private $actualDatesCalculator;

    /**
     * RecalculatePhaseDatesSubscriber constructor.
     *
     * @param DateRangeCalculatorInterface $actualDatesCalculator
     */
    public function __construct(DateRangeCalculatorInterface $actualDatesCalculator)
    {
        $this->actualDatesCalculator = $actualDatesCalculator;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            WorkPackageEvents::RECALCULATE_PHASE_DATES => 'recalculateDates',
        ];
    }

    /**
     * @param WorkPackageEvent $event
     */
    public function recalculateDates(WorkPackageEvent $event)
    {
        $wp = $event->getWorkPackage();
        if (!$wp->isPhase()) {
            return;
        }

        $this->recalculateActualDates($wp);
    }

    /**
     * @param WorkPackage $wp
     */
    private function recalculateActualDates(WorkPackage $wp)
    {
        $range = $this->actualDatesCalculator->calculate($wp);

        $wp->setActualStartAt($range->getStart());
        $wp->setActualFinishAt($range->getFinish());
    }
}
