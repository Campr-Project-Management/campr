<?php

namespace AppBundle\Serializer\EventListener;

use Component\Uploader\Resolver\UrlResolverInterface;
use AppBundle\Entity\Project;
use Component\Cost\Calculator\ProjectTotalCostCalculatorInterface;
use Component\Project\Calculator\DateRangeCalculatorInterface;
use Component\Project\Calculator\ProjectProgressCalculatorInterface;
use JMS\Serializer\EventDispatcher\Events;
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
     * @var ProjectTotalCostCalculatorInterface
     */
    private $projectTotalCostCalculator;

    /**
     * @var ProjectProgressCalculatorInterface
     */
    private $progressCalculator;

    /**
     * @var UrlResolverInterface
     */
    private $logoUrlResolver;

    /**
     * ProjectSubscriber constructor.
     *
     * @param DateRangeCalculatorInterface        $scheduledDatesCalculator
     * @param DateRangeCalculatorInterface        $forecastDatesCalculator
     * @param DateRangeCalculatorInterface        $actualDatesCalculator
     * @param ProjectTotalCostCalculatorInterface $projectTotalCostCalculator
     * @param ProjectProgressCalculatorInterface  $progressCalculator
     * @param UrlResolverInterface                $logoUrlResolver
     */
    public function __construct(
        DateRangeCalculatorInterface $scheduledDatesCalculator,
        DateRangeCalculatorInterface $forecastDatesCalculator,
        DateRangeCalculatorInterface $actualDatesCalculator,
        ProjectTotalCostCalculatorInterface $projectTotalCostCalculator,
        ProjectProgressCalculatorInterface $progressCalculator,
        UrlResolverInterface $logoUrlResolver
    ) {
        $this->scheduledDatesCalculator = $scheduledDatesCalculator;
        $this->forecastDatesCalculator = $forecastDatesCalculator;
        $this->actualDatesCalculator = $actualDatesCalculator;
        $this->projectTotalCostCalculator = $projectTotalCostCalculator;
        $this->progressCalculator = $progressCalculator;
        $this->logoUrlResolver = $logoUrlResolver;
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

        $this->addDates($visitor, $project);
        $this->addCostProgress($visitor, $project);
        $this->addProgress($visitor, $project);
        $this->addLogoUrl($visitor, $project);
    }

    /**
     * @param GenericSerializationVisitor $visitor
     * @param Project                     $project
     */
    private function addDates(GenericSerializationVisitor $visitor, Project $project)
    {
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
     * @return string|null
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
     * @param Project                     $project
     */
    private function addCostProgress(GenericSerializationVisitor $visitor, Project $project)
    {
        $total = $this->projectTotalCostCalculator->calculate($project);

        $visitor->setData('costProgress', $total->getProgress());
    }

    /**
     * @param GenericSerializationVisitor $visitor
     * @param Project                     $project
     */
    private function addProgress(GenericSerializationVisitor $visitor, Project $project)
    {
        $visitor->setData('progress', $this->progressCalculator->calculate($project));
    }

    /**
     * @param GenericSerializationVisitor $visitor
     * @param Project                     $project
     */
    private function addLogoUrl(GenericSerializationVisitor $visitor, Project $project)
    {
        $logo = $this->logoUrlResolver->resolve($project);
        $visitor->setData('logoUrl', $logo);
    }
}
