<?php

namespace Component\WorkPackage\Calculator;

use AppBundle\Entity\WorkPackage;
use AppBundle\Repository\WorkPackageRepository;
use Component\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;

class PhaseActualDatesCalculator implements DateRangeCalculatorInterface
{
    /**
     * @var RepositoryInterface
     */
    private $workPackageRepository;

    /**
     * PhaseActualDatesCalculator constructor.
     *
     * @param WorkPackageRepository $workPackageRepository
     */
    public function __construct(WorkPackageRepository $workPackageRepository)
    {
        $this->workPackageRepository = $workPackageRepository;
    }

    /**
     * @param WorkPackage $workPackage
     *
     * @return \DateTime[]
     */
    public function calculate(WorkPackage $workPackage): array
    {
        Assert::true($workPackage->isPhase(), 'Task is not a phase');

        $startAt = $this->workPackageRepository->getPhaseActualStartDate($workPackage);
        $finishAt = $this->workPackageRepository->getPhaseActualFinishDate($workPackage);

        if ($startAt) {
            $startAt = new \DateTime($startAt);
        }

        if ($finishAt) {
            $finishAt = new \DateTime($finishAt);
        }

        return [$startAt, $finishAt];
    }
}
