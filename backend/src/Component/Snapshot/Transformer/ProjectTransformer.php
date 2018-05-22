<?php

namespace Component\Snapshot\Transformer;

use AppBundle\Entity\Project;
use Component\Project\Calculator\DateRangeCalculatorInterface;

class ProjectTransformer extends AbstractTransformer
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
     * @var TransformerInterface
     */
    private $dateTransformer;

    /**
     * @var TransformerInterface
     */
    private $tasksTransformer;

    /**
     * @var TransformerInterface
     */
    private $costsTransformer;

    /**
     * @var TransformerInterface
     */
    private $risksTransformer;

    /**
     * @var TransformerInterface
     */
    private $opportunitiesTransformer;

    /**
     * @var TransformerInterface
     */
    private $todosTransformer;

    /**
     * @var TransformerInterface
     */
    private $decisionsTransformer;

    /**
     * @var TransformerInterface
     */
    private $phasesTransformer;

    /**
     * @var TransformerInterface
     */
    private $milestonesTransformer;

    /**
     * ProjectTransformer constructor.
     *
     * @param TransformerInterface         $dateTransformer
     * @param DateRangeCalculatorInterface $scheduledDatesCalculator
     * @param DateRangeCalculatorInterface $forecastDatesCalculator
     * @param DateRangeCalculatorInterface $actualDatesCalculator
     * @param TransformerInterface         $tasksTransformer
     * @param TransformerInterface         $costsTransformer
     * @param TransformerInterface         $risksTransformer
     * @param TransformerInterface         $opportunitiesTransformer
     * @param TransformerInterface         $todosTransformer
     * @param TransformerInterface         $decisionsTransformer
     * @param TransformerInterface         $phasesTransformer
     * @param TransformerInterface         $milestonesTransformer
     */
    public function __construct(
        TransformerInterface $dateTransformer,
        DateRangeCalculatorInterface $scheduledDatesCalculator,
        DateRangeCalculatorInterface $forecastDatesCalculator,
        DateRangeCalculatorInterface $actualDatesCalculator,
        TransformerInterface $tasksTransformer,
        TransformerInterface $costsTransformer,
        TransformerInterface $risksTransformer,
        TransformerInterface $opportunitiesTransformer,
        TransformerInterface $todosTransformer,
        TransformerInterface $decisionsTransformer,
        TransformerInterface $phasesTransformer,
        TransformerInterface $milestonesTransformer
    ) {
        $this->dateTransformer = $dateTransformer;
        $this->scheduledDatesCalculator = $scheduledDatesCalculator;
        $this->forecastDatesCalculator = $forecastDatesCalculator;
        $this->actualDatesCalculator = $actualDatesCalculator;

        $this->tasksTransformer = $tasksTransformer;
        $this->costsTransformer = $costsTransformer;
        $this->risksTransformer = $risksTransformer;
        $this->opportunitiesTransformer = $opportunitiesTransformer;
        $this->todosTransformer = $todosTransformer;
        $this->decisionsTransformer = $decisionsTransformer;
        $this->phasesTransformer = $phasesTransformer;
        $this->milestonesTransformer = $milestonesTransformer;
    }

    /**
     * @param Project $project
     *
     * @return array
     */
    protected function doTransform($project): array
    {
        $data = [
            'id' => $project->getId(),
            'number' => $project->getNumber(),
            'name' => $project->getName(),
            'currency' => [
                'code' => $project->getCurrency()->getCode(),
                'symbol' => $project->getCurrency()->getSymbol(),
            ],
            'trafficLight' => $project->getTrafficLight(),
            'schedule' => $this->getScheduleData($project),
            'tasks' => $this->tasksTransformer->transform($project),
            'phases' => $this->phasesTransformer->transform($project),
            'milestones' => $this->milestonesTransformer->transform($project),
            'costs' => $this->costsTransformer->transform($project),
            'risks' => $this->risksTransformer->transform($project),
            'opportunities' => $this->opportunitiesTransformer->transform($project),
            'decisions' => $this->decisionsTransformer->transform($project),
            'todos' => $this->todosTransformer->transform($project),
        ];

        return $data;
    }

    /**
     * @param Project $project
     *
     * @return array
     */
    private function getScheduleData(Project $project): array
    {
        $baseRange = $this->scheduledDatesCalculator->calculate($project);
        $forecastRange = $this->forecastDatesCalculator->calculate($project);
        $actualRange = $this->actualDatesCalculator->calculate($project);

        return [
            'scheduled' => [
                'startAt' => $this->dateTransformer->transform($baseRange->getStart()),
                'finishAt' => $this->dateTransformer->transform($baseRange->getFinish()),
                'durationDays' => $baseRange->getDurationDays(),
            ],
            'forecast' => [
                'startAt' => $this->dateTransformer->transform($forecastRange->getStart()),
                'finishAt' => $this->dateTransformer->transform($forecastRange->getFinish()),
                'durationDays' => $forecastRange->getDurationDays(),
            ],
            'actual' => [
                'startAt' => $this->dateTransformer->transform($actualRange->getStart()),
                'finishAt' => $this->dateTransformer->transform($actualRange->getFinish()),
                'durationDays' => $actualRange->getDurationDays(),
            ],
        ];
    }

    /**
     * @param mixed $object
     *
     * @return bool
     */
    public function support($object): bool
    {
        return $object instanceof Project;
    }
}
