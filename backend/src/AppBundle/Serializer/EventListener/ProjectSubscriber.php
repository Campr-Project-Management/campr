<?php

namespace AppBundle\Serializer\EventListener;

use AppBundle\Entity\Project;
use Component\Project\Calculator\DateRangeCalculatorInterface;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\GenericSerializationVisitor;

class ProjectSubscriber implements EventSubscriberInterface
{
    /**
     * @var DateRangeCalculatorInterface
     */
    private $scheduledDatesCalculator;

    /**
     * @var DateRangeCalculatorInterface
     */
    private $forecastDatesCalculator;

    /**
     * @var DateRangeCalculatorInterface
     */
    private $actualDatesCalculator;

    /**
     * ProjectSubscriber constructor.
     *
     * @param DateRangeCalculatorInterface $scheduledDatesCalculator
     * @param DateRangeCalculatorInterface $forecastDatesCalculator
     * @param DateRangeCalculatorInterface $actualDatesCalculator
     */
    public function __construct(
        DateRangeCalculatorInterface $scheduledDatesCalculator,
        DateRangeCalculatorInterface $forecastDatesCalculator,
        DateRangeCalculatorInterface $actualDatesCalculator
    ) {
        $this->scheduledDatesCalculator = $scheduledDatesCalculator;
        $this->forecastDatesCalculator = $forecastDatesCalculator;
        $this->actualDatesCalculator = $actualDatesCalculator;
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
                'class' => Project::class,
            ],
        ];
    }

    /**
     * @param ObjectEvent $event
     */
    public function onPostSerialize(ObjectEvent $event)
    {
        /** @var Project $project */
        $project = $event->getObject();

        /** @var GenericSerializationVisitor $visitor */
        $visitor = $event->getVisitor();

        $scheduled = $this->scheduledDatesCalculator->calculate($project);
        $visitor->setData('scheduledStartAt', $this->dateToString($scheduled->getStart()));
        $visitor->setData('scheduledFinishAt', $this->dateToString($scheduled->getFinish()));
        $visitor->setData('scheduledDurationDays', $scheduled->getDurationDays());

        $forecast = $this->forecastDatesCalculator->calculate($project);
        $visitor->setData('forecastStartAt', $this->dateToString($forecast->getStart()));
        $visitor->setData('forecastFinishAt', $this->dateToString($forecast->getFinish()));
        $visitor->setData('forecastDurationDays', $forecast->getDurationDays());

        $actual = $this->actualDatesCalculator->calculate($project);
        $visitor->setData('actualStartAt', $this->dateToString($actual->getStart()));
        $visitor->setData('actualFinishAt', $this->dateToString($actual->getFinish()));
        $visitor->setData('actualDurationDays', $actual->getDurationDays());
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
