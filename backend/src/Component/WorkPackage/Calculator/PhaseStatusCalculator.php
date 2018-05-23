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
            ],
            WorkPackageStatus::CODE_PENDING,
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
    protected function getStatusesCodes(WorkPackage $workPackage)
    {
        $codes = [];
        foreach ($this->getStatuses() as $status) {
            $count = $this->workPackageRepository->getStatusCountByPhase($workPackage, $status);
            if (!$count) {
                continue;
            }

            $codes[] = $status->getCode();
        }

        return $codes;
    }

    /**
     * @param WorkPackage $workPackage
     *
     * @return WorkPackageStatus|null
     */
    private function calculateStatus(WorkPackage $workPackage)
    {
        $codes = $this->getStatusesCodes($workPackage);
        $code = $this->calculateStatusCode($codes);

        return $this->findStatusByCode($code);
    }

    /**
     * @param array $codes
     *
     * @return string
     */
    private function calculateStatusCode(array $codes): string
    {
        $codes = array_unique($codes);
        Assert::notEmpty($codes);

        if (1 === count($codes)) {
            return array_shift($codes);
        }

        foreach (self::$rules as $rule) {
            if (!in_array($rule[0], $codes)) {
                continue;
            }

            $diff = array_diff(
                array_filter(
                    $codes,
                    function (string $code) use ($rule) {
                        return $code !== $rule[0];
                    }
                ),
                $rule[1]
            );

            if (!empty($diff)) {
                continue;
            }

            return $rule[2];
        }

        return WorkPackageStatus::CODE_OPEN;
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
