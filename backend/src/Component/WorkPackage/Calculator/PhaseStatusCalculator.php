<?php

namespace Component\WorkPackage\Calculator;

use AppBundle\Entity\WorkPackage;
use AppBundle\Entity\WorkPackageStatus;
use AppBundle\Repository\WorkPackageRepository;
use AppBundle\Repository\WorkPackageStatusRepository;
use Webmozart\Assert\Assert;

class PhaseStatusCalculator implements StatusCalculatorInterface
{
    private static $rules = [
        [
            WorkPackageStatus::CODE_ONGOING,
            [
                WorkPackageStatus::CODE_PENDING,
                WorkPackageStatus::CODE_COMPLETED,
                WorkPackageStatus::CODE_CLOSED,
                WorkPackageStatus::CODE_OPEN,
            ],
            WorkPackageStatus::CODE_ONGOING,
        ],
        [
            WorkPackageStatus::CODE_PENDING,
            [
                WorkPackageStatus::CODE_OPEN,
                WorkPackageStatus::CODE_ONGOING,
                WorkPackageStatus::CODE_COMPLETED,
                WorkPackageStatus::CODE_CLOSED,
            ],
            WorkPackageStatus::CODE_ONGOING,
        ],
        [
            WorkPackageStatus::CODE_PENDING,
            [
                WorkPackageStatus::OPEN,
            ],
            WorkPackageStatus::CODE_PENDING,
        ],
        [
            WorkPackageStatus::CODE_COMPLETED,
            [
                WorkPackageStatus::CODE_CLOSED,
            ],
            WorkPackageStatus::CODE_COMPLETED,
        ],
    ];

    /**
     * @var WorkPackage
     */
    protected $workPackageRepository;

    /**
     * @var WorkPackageStatusRepository
     */
    protected $workPackageStatusRepository;

    /**
     * @param WorkPackageRepository       $workPackageRepository
     * @param WorkPackageStatusRepository $workPackageStatusRepository
     */
    public function __construct(
        WorkPackageRepository $workPackageRepository,
        WorkPackageStatusRepository $workPackageStatusRepository
    ) {
        $this->workPackageRepository = $workPackageRepository;
        $this->workPackageStatusRepository = $workPackageStatusRepository;
    }

    /**
     * @param WorkPackage $workPackage
     *
     * @return WorkPackageStatus
     */
    public function calculate(WorkPackage $workPackage): WorkPackageStatus
    {
        Assert::true($this->isSupported($workPackage), 'WorkPackage is not a phase');

        $status = $this->calculateStatus($workPackage);
        if (!$status) {
            $status = $this->workPackageStatusRepository->getDefault();
        }

        return $status;
    }

    /**
     * @param WorkPackage $workPackage
     *
     * @return bool
     */
    protected function isSupported(WorkPackage $workPackage)
    {
        return $workPackage->isPhase();
    }

    /**
     * @param WorkPackage $workPackage
     *
     * @return array
     */
    protected function getStatusesCounts(WorkPackage $workPackage)
    {
        $data = [];
        foreach ($this->getStatuses() as $status) {
            $data[$status->getCode()] = $this->workPackageRepository->getStatusCountByPhase($workPackage, $status);
        }

        return $data;
    }

    /**
     * @param WorkPackage $workPackage
     *
     * @return WorkPackageStatus|null
     */
    private function calculateStatus(WorkPackage $workPackage)
    {
        $statusesCounts = $this->getStatusesCounts($workPackage);
        $statusesCounts = array_filter(
            $statusesCounts,
            function ($count) {
                return $count > 0;
            }
        );

        if (1 === count($statusesCounts)) {
            $code = key($statusesCounts);

            return $this->findStatusByCode($code);
        }

        foreach (self::$rules as $rule) {
            if ($this->hasStatus($statusesCounts, $rule[0]) && $this->hasStatus($statusesCounts, ...$rule[1])) {
                return $this->findStatusByCode($rule[2]);
            }
        }

        return null;
    }

    /**
     * @param array  $statusesCounts
     * @param string ...$codes
     *
     * @return bool
     */
    private function hasStatus(array $statusesCounts, string ...$codes): bool
    {
        foreach ($codes as $code) {
            if (isset($statusesCounts[$code]) && $statusesCounts[$code] > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return WorkPackageStatus[]
     */
    protected function getStatuses(): array
    {
        return $this->workPackageStatusRepository->findAll();
    }

    /**
     * @param string $code
     *
     * @return WorkPackageStatus
     */
    private function findStatusByCode(string $code): WorkPackageStatus
    {
        /** @var WorkPackageStatus $status */
        $status = $this->workPackageStatusRepository->findOneBy(['code' => $code]);

        return $status;
    }
}
