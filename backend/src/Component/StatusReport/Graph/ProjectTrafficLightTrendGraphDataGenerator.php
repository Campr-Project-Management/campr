<?php

namespace Component\StatusReport\Graph;

use AppBundle\Entity\StatusReport;
use Component\TrafficLight\TrafficLight;
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
     * @param array $reports
     *
     * @return array
     */
    public function generate(array $reports): array
    {
        $data = [];
        $reports = $this->prepareReports($reports);
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
                'week' => $report->getCreatedAt()->format('W'),
                'value' => $total,
                'color' => (new TrafficLight($report->getProjectTrafficLight()))->__toString(),
            ];
            $lastReport = $report;
        }

        return $data;
    }

    /**
     * @param StatusReport[] $reports
     *
     * @return StatusReport[]
     */
    private function prepareReports(array $reports): array
    {
        $reports = $this->stripInvalidReports($reports);
        $reports = $this->sortByDate($reports);

        return $this->getWeeklyReports($reports);
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
     * @param StatusReport[] $reports
     *
     * @return StatusReport[]
     */
    private function getWeeklyReports(array $reports): array
    {
        $reports = $this->sortByDate($reports);
        $results = [];
        foreach ($reports as $report) {
            $week = $report->getWeekNumber();
            $results[$week] = $report;
        }

        return array_values($results);
    }

    /**
     * @param StatusReport[] $reports
     *
     * @return StatusReport[]
     */
    private function sortByDate(array $reports): array
    {
        usort(
            $reports,
            function (StatusReport $report1, StatusReport $report2) {
                return $report1->getCreatedAt() <=> $report2->getCreatedAt();
            }
        );

        return $reports;
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
