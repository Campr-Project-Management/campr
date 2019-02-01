<?php

namespace Component\StatusReport\Graph;

use AppBundle\Entity\StatusReport;
use Component\StatusReport\Aggregator\StatusReportsAggregatorInterface;
use Component\TrafficLight\TrafficLight;
use Symfony\Component\Translation\TranslatorInterface;
use Webmozart\Assert\Assert;

class ProjectTrafficLightTrendGraphDataGenerator
{
    /**
     * @var array
     */
    private static $rules = [
        TrafficLight::GREEN => [
            TrafficLight::GREEN => 1,
            TrafficLight::YELLOW => -1,
            TrafficLight::RED => -2,
        ],
        TrafficLight::YELLOW => [
            TrafficLight::GREEN => 1,
            TrafficLight::YELLOW => 0,
            TrafficLight::RED => -1,
        ],
        TrafficLight::RED => [
            TrafficLight::GREEN => 2,
            TrafficLight::YELLOW => 1,
            TrafficLight::RED => -1,
        ],
    ];
    /**
     * @var array
     */
    private $aggregators = [];

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * ProjectTrafficLightTrendGraphDataGenerator constructor.
     *
     * @param StatusReportsAggregatorInterface[] $aggregators
     * @param TranslatorInterface                $translator
     */
    public function __construct(array $aggregators, TranslatorInterface $translator)
    {
        foreach ($aggregators as $aggregator) {
            $this->aggregators[$aggregator->getType()] = $aggregator;
        }
        $this->translator = $translator;
    }

    /**
     * @param array  $reports
     * @param string $aggregatorType
     *
     * @return array
     */
    public function generate(
        array $reports,
        string $aggregatorType = StatusReportsAggregatorInterface::TYPE_WEEKLY
    ): array {
        $data = [];
        $reports = $this->stripInvalidReports($reports);
        $aggregator = $this->getAggregator($aggregatorType);
        $reports = $aggregator->aggregate($reports);
        if (count($reports) < 2) {
            return $data;
        }

        $data = [];
        $lastReport = null;
        $total = 0;
        foreach ($reports as $index => $report) {
            if ($index > 0) {
                $total += $this->calculateTransition($lastReport, $report);
            }

            $data[] = [
                'date' => $report->getCreatedAt()->format('Y-m-d'),
                'label' => $this->getLabel($report, $aggregator),
                'value' => $total,
                'color' => (new TrafficLight($report->getProjectTrafficLight()))->__toString(),
            ];
            $lastReport = $report;
        }

        return $data;
    }

    /**
     * @param StatusReport                     $statusReport
     * @param StatusReportsAggregatorInterface $aggregator
     *
     * @return string
     */
    private function getLabel(StatusReport $statusReport, StatusReportsAggregatorInterface $aggregator): string
    {
        if (StatusReportsAggregatorInterface::TYPE_DAILY === $aggregator->getType()) {
            return $statusReport->getCreatedAt()->format('d/m/Y');
        }

        if (in_array(
            $aggregator->getType(),
            [StatusReportsAggregatorInterface::TYPE_WEEKLY, StatusReportsAggregatorInterface::TYPE_BIWEEKLY]
        )) {
            return sprintf(
                '%s %s',
                $this->translator->trans('message.week'),
                $statusReport->getCreatedAt()->format('W, y')
            );
        }

        if (StatusReportsAggregatorInterface::TYPE_MONTHLY === $aggregator->getType()) {
            return $statusReport->getCreatedAt()->format('M y');
        }

        throw new \InvalidArgumentException('Invalid aggregator type: %s', $aggregator->getType());
    }

    /**
     * @param string $type
     *
     * @return StatusReportsAggregatorInterface
     */
    private function getAggregator(string $type): StatusReportsAggregatorInterface
    {
        Assert::true(
            isset($this->aggregators[$type]),
            sprintf(
                'Unknown aggregator type: %s; Must be one of: %s',
                $type,
                implode(', ', array_keys($this->aggregators))
            )
        );

        return $this->aggregators[$type];
    }

    /**
     * @param StatusReport[] $reports
     *
     * @return StatusReport[]
     */
    private function stripInvalidReports(array $reports): array
    {
        return array_filter(
            $reports,
            function (StatusReport $report) {
                return !is_null($report->getProjectTrafficLight());
            }
        );
    }

    /**
     * @param StatusReport $report1
     * @param StatusReport $report2
     *
     * @return int
     */
    private function calculateTransition(StatusReport $report1, StatusReport $report2): int
    {
        Assert::notNull($report1->getProjectTrafficLight());
        Assert::notNull($report2->getProjectTrafficLight());
        Assert::true(
            isset(self::$rules[$report1->getProjectTrafficLight()][$report2->getProjectTrafficLight()]),
            sprintf(
                'Undefined project trend rule: (%s, %s)',
                new TrafficLight($report1->getProjectTrafficLight()),
                new TrafficLight($report2->getProjectTrafficLight())
            )
        );

        return self::$rules[$report1->getProjectTrafficLight()][$report2->getProjectTrafficLight()];
    }
}
