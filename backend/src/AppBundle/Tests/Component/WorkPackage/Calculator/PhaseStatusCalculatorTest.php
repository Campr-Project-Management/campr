<?php

namespace AppBundle\Tests\Component\WorkPackage\Calculator;

use AppBundle\Entity\WorkPackageStatus;
use AppBundle\Repository\WorkPackageRepository;
use AppBundle\Repository\WorkPackageStatusRepository;
use Component\WorkPackage\Calculator\PhaseStatusCalculator;
use AppBundle\Tests\CallMethodTrait;

class PhaseStatusCalculatorTest extends \PHPUnit_Framework_TestCase
{
    use CallMethodTrait;

    /**
     * @var PhaseStatusCalculator
     */
    private $calculator;

    /**
     * @var WorkPackageRepository
     */
    private $workPackageRepository;

    /**
     * @var WorkPackageStatusRepository
     */
    private $workPackageStatusRepository;

    public function setUp()
    {
        $this->workPackageRepository = $this
            ->getMockBuilder(WorkPackageRepository::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->workPackageStatusRepository = $this
            ->getMockBuilder(WorkPackageStatusRepository::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->calculator = new PhaseStatusCalculator($this->workPackageRepository, $this->workPackageStatusRepository);
    }

    public function tearDown()
    {
        $this->workPackageStatusRepository = null;
        $this->workPackageRepository = null;
        $this->calculator = null;
    }

    public function testCalculateStatusCodeSingleStatus()
    {
        $result = $this->callMethod(
            $this->calculator,
            'calculateStatusCode',
            [[WorkPackageStatus::CODE_PENDING]]
        );

        $this->assertEquals(WorkPackageStatus::CODE_PENDING, $result);
    }

    /**
     * @dataProvider calculateStatusCodeDataProvider
     *
     * @param array  $codes
     * @param string $expected
     */
    public function testCalculateStatusCode(array $codes, string $expected)
    {
        // Pending + Open = Pending
        $actual = $this->callMethod(
            $this->calculator,
            'calculateStatusCode',
            [$codes]
        );

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return array
     */
    public function calculateStatusCodeDataProvider(): array
    {
        return [
            [
                [
                    WorkPackageStatus::CODE_PENDING,
                    WorkPackageStatus::CODE_OPEN,
                    WorkPackageStatus::CODE_OPEN,
                ],
                WorkPackageStatus::CODE_PENDING,
            ],
            [
                [
                    WorkPackageStatus::CODE_OPEN,
                    WorkPackageStatus::CODE_OPEN,
                    WorkPackageStatus::CODE_PENDING,
                ],
                WorkPackageStatus::CODE_PENDING,
            ],
            [
                [
                    WorkPackageStatus::CODE_ONGOING,
                    WorkPackageStatus::CODE_PENDING,
                    WorkPackageStatus::CODE_COMPLETED,
                    WorkPackageStatus::CODE_CLOSED,
                ],
                WorkPackageStatus::CODE_ONGOING,
            ],
            [
                [
                    WorkPackageStatus::CODE_ONGOING,
                    WorkPackageStatus::CODE_ONGOING,
                    WorkPackageStatus::CODE_COMPLETED,
                    WorkPackageStatus::CODE_CLOSED,
                ],
                WorkPackageStatus::CODE_ONGOING,
            ],
            [
                [
                    WorkPackageStatus::CODE_PENDING,
                    WorkPackageStatus::CODE_ONGOING,
                ],
                WorkPackageStatus::CODE_ONGOING,
            ],
            [
                [
                    WorkPackageStatus::CODE_PENDING,
                    WorkPackageStatus::CODE_COMPLETED,
                    WorkPackageStatus::CODE_CLOSED,
                ],
                WorkPackageStatus::CODE_ONGOING,
            ],
            [
                [
                    WorkPackageStatus::CODE_COMPLETED,
                    WorkPackageStatus::CODE_CLOSED,
                ],
                WorkPackageStatus::CODE_COMPLETED,
            ],
            [
                [
                    WorkPackageStatus::CODE_OPEN,
                ],
                WorkPackageStatus::CODE_OPEN,
            ],
            [
                [
                    WorkPackageStatus::CODE_PENDING,
                ],
                WorkPackageStatus::CODE_PENDING,
            ],
            [
                [
                    WorkPackageStatus::CODE_ONGOING,
                ],
                WorkPackageStatus::CODE_ONGOING,
            ],
            [
                [
                    WorkPackageStatus::CODE_COMPLETED,
                ],
                WorkPackageStatus::CODE_COMPLETED,
            ],
            [
                [
                    WorkPackageStatus::CODE_CLOSED,
                ],
                WorkPackageStatus::CODE_CLOSED,
            ],
        ];
    }
}
