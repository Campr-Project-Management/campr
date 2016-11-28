<?php

namespace AppBundle\Tests\Services;

use AppBundle\Entity\Assignment;
use AppBundle\Entity\Calendar;
use AppBundle\Entity\Day;
use AppBundle\Entity\Project;
use AppBundle\Entity\Timephase;
use AppBundle\Entity\WorkingTime;
use AppBundle\Entity\WorkPackage;
use AppBundle\Entity\WorkPackageProjectWorkCostType;
use AppBundle\Repository\CalendarRepository;
use AppBundle\Repository\ProjectRepository;
use AppBundle\Repository\WorkPackageProjectWorkCostTypeRepository;
use AppBundle\Repository\WorkPackageRepository;
use AppBundle\Services\ImportService;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Finder\Finder;

class ImportServiceTest extends KernelTestCase
{
    const IMPORT_CALENDARS = 'import_calendars_sample.xml';
    const IMPORT_ASSIGNMENTS = 'import_assignments_sample.xml';
    const IMPORT_TASKS = 'import_tasks_sample.xml';
    const IMPORT_RESOURCES = 'import_resources_sample.xml';

    /** @var ContainerInterface */
    private $container;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $entityManager;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $projectRepository;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $calendarRepository;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $workPackageRepository;

    /** @var ImportService */
    private $importService;

    /** @var Finder */
    private $finder;

    /** @var Finder */
    private $files;

    /** @var string */
    private $path;

    public function setUp()
    {
        self::bootKernel();

        $this->entityManager = $this
            ->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $this->projectRepository = $this
            ->getMockBuilder(ProjectRepository::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $this->calendarRepository = $this
            ->getMockBuilder(CalendarRepository::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $this->workPackageRepository = $this
            ->getMockBuilder(WorkPackageRepository::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this
            ->projectRepository
            ->expects($this->once())
            ->method('findBy')
            ->willReturn(null)
        ;
        $this
            ->entityManager
            ->expects($this->atLeastOnce())
            ->method('flush')
            ->willReturn(null)
        ;

        $this->container = self::$kernel->getContainer();
        $this->path = $this->container->getParameter('tests_import_folder');
        $this->finder = new Finder();
        $this->importService = new ImportService($this->entityManager);
    }

    public function testImportCalendars()
    {
        $self = $this;

        $this
            ->entityManager
            ->expects($this->atLeastOnce())
            ->method('getRepository')
            ->willReturnMap([
                [Project::class, $this->projectRepository],
                [Calendar::class, $this->calendarRepository],
            ])
        ;
        $this
            ->calendarRepository
            ->expects($this->any())
            ->method('find')
            ->willReturn(null)
        ;
        $this
            ->entityManager
            ->expects($this->atLeastOnce())
            ->method('persist')
            ->willReturnCallback(function ($object) use ($self) {
                if ($object instanceof Project) {
                    $self->assertEquals('151215_Fokussierung HQS SP1 SF R3.xml', $object->getName());
                    $self->assertEquals(14, $object->getNumber());
                    $self->assertEquals('2015-12-13 08:00:00', $object->getCreatedAt()->format('Y-m-d H:i:s'));
                    $self->assertEquals('2016-10-05 14:02:00', $object->getUpdatedAt()->format('Y-m-d H:i:s'));
                }

                if ($object instanceof Day) {
                    $self->assertEquals(1, $object->getType());
                    $self->assertEquals(1, $object->getWorking());
                    $self->assertEquals('Standard', $object->getCalendar()->getName());
                }

                if ($object instanceof Calendar) {
                    $self->assertEquals('Standard', $object->getName());
                    $self->assertEquals(1, $object->getIsBased());
                    $self->assertEquals(0, $object->getIsBaseline());
                    $self->assertEquals(null, $object->getParent());
                    $self->assertEquals('151215_Fokussierung HQS SP1 SF R3.xml', $object->getProject()->getName());
                }

                if ($object instanceof WorkingTime) {
                    $self->assertEquals('08:00', $object->getFromTimeFormatted());
                    $self->assertEquals('12:00', $object->getToTimeFormatted());
                    $self->assertEquals(1, $object->getDay()->getType());
                }
            })
        ;

        $this->files = $this->finder->files()->in($this->path)->name(self::IMPORT_CALENDARS);
        foreach ($this->files as $file) {
            $content = file_get_contents($file->getRealPath());
            $this->importService->importProjects($content);
        }
    }

    public function testImportTasks()
    {
        $self = $this;

        $this
            ->entityManager
            ->expects($this->atLeastOnce())
            ->method('getRepository')
            ->willReturnMap([
                [Project::class, $this->projectRepository],
                [WorkPackage::class, $this->workPackageRepository],
                [Calendar::class, $this->calendarRepository],
            ])
        ;
        $this
            ->workPackageRepository
            ->expects($this->atLeastOnce())
            ->method('find')
            ->willReturn(null)
        ;
        $this
            ->entityManager
            ->expects($this->atLeastOnce())
            ->method('persist')
            ->willReturnCallback(function ($object) use ($self) {
                if ($object instanceof Project) {
                    $self->assertEquals('151215_Fokussierung HQS SP1 SF R3.xml', $object->getName());
                    $self->assertEquals(14, $object->getNumber());
                    $self->assertEquals('2015-12-13 08:00:00', $object->getCreatedAt()->format('Y-m-d H:i:s'));
                    $self->assertEquals('2016-10-05 14:02:00', $object->getUpdatedAt()->format('Y-m-d H:i:s'));
                }

                if ($object instanceof WorkPackage) {
                    $self->assertEquals(0, $object->getPuid());
                    $self->assertEquals('2015-12-13 07:43:00', $object->getCreatedAt()->format('Y-m-d H:i:s'));
                    $self->assertEquals('2015-10-08 08:00:00', $object->getScheduledStartAt()->format('Y-m-d H:i:s'));
                    $self->assertEquals('2018-05-11 17:00:00', $object->getScheduledFinishAt()->format('Y-m-d H:i:s'));
                    $self->assertEquals('151215_Fokussierung HQS SP1 SF R3.xml', $object->getProject()->getName());
                }
            })
        ;

        $this->files = $this->finder->files()->in($this->path)->name(self::IMPORT_TASKS);
        foreach ($this->files as $file) {
            $content = file_get_contents($file->getRealPath());
            $this->importService->importProjects($content);
        }
    }

    public function testImportResources()
    {
        $self = $this;

        $this
            ->entityManager
            ->expects($this->atLeastOnce())
            ->method('getRepository')
            ->willReturnMap([
                [Project::class, $this->projectRepository],
                [Calendar::class, $this->calendarRepository],
            ])
        ;
        $this
            ->calendarRepository
            ->expects($this->atLeastOnce())
            ->method('find')
            ->willReturn(null)
        ;
        $this
            ->entityManager
            ->expects($this->atLeastOnce())
            ->method('persist')
            ->willReturnCallback(function ($object) use ($self) {
                if ($object instanceof Project) {
                    $self->assertEquals('151215_Fokussierung HQS SP1 SF R3.xml', $object->getName());
                    $self->assertEquals(14, $object->getNumber());
                    $self->assertEquals('2015-12-13 08:00:00', $object->getCreatedAt()->format('Y-m-d H:i:s'));
                    $self->assertEquals('2016-10-05 14:02:00', $object->getUpdatedAt()->format('Y-m-d H:i:s'));
                }

                if ($object instanceof WorkPackageProjectWorkCostType) {
                    $self->assertEquals(0, $object->getIsGeneric());
                    $self->assertEquals(0, $object->getIsInactive());
                    $self->assertEquals(0, $object->getIsEnterprise());
                    $self->assertEquals(0, $object->getIsCostResource());
                    $self->assertEquals(0, $object->getIsBudget());
                    $self->assertEquals('2015-12-13 07:43:00', $object->getCreatedAt()->format('Y-m-d H:i:s'));
                }
            })
        ;

        $this->files = $this->finder->files()->in($this->path)->name(self::IMPORT_RESOURCES);
        foreach ($this->files as $file) {
            $content = file_get_contents($file->getRealPath());
            $this->importService->importProjects($content);
        }
    }

    public function testImportAssigments()
    {
        $self = $this;

        $workPackageProjectWorkCostTypeRepository = $this
            ->getMockBuilder(WorkPackageProjectWorkCostTypeRepository::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $this
            ->entityManager
            ->expects($this->atLeastOnce())
            ->method('getRepository')
            ->willReturnMap([
                [Project::class, $this->projectRepository],
                [WorkPackage::class, $this->workPackageRepository],
                [WorkPackageProjectWorkCostType::class, $workPackageProjectWorkCostTypeRepository],
            ])
        ;
        $this
            ->workPackageRepository
            ->expects($this->atLeastOnce())
            ->method('find')
            ->willReturn(null)
        ;
        $workPackageProjectWorkCostTypeRepository
            ->expects($this->atLeastOnce())
            ->method('find')
            ->willReturn(null)
        ;
        $this
            ->entityManager
            ->expects($this->atLeastOnce())
            ->method('persist')
            ->willReturnCallback(function ($object) use ($self) {
                if ($object instanceof Project) {
                    $self->assertEquals('151215_Fokussierung HQS SP1 SF R3.xml', $object->getName());
                    $self->assertEquals(14, $object->getNumber());
                    $self->assertEquals('2015-12-13 08:00:00', $object->getCreatedAt()->format('Y-m-d H:i:s'));
                    $self->assertEquals('2016-10-05 14:02:00', $object->getUpdatedAt()->format('Y-m-d H:i:s'));
                }

                if ($object instanceof Assignment) {
                    $self->assertEquals(0, $object->getPercentWorkComplete());
                    $self->assertEquals(0, $object->getMilestone());
                    $self->assertEquals(0, $object->getConfirmed());
                    $self->assertEquals('2015-12-13 07:43:00', $object->getCreatedAt()->format('Y-m-d H:i:s'));
                    $self->assertEquals('2016-11-07 08:00:00', $object->getStartedAt()->format('Y-m-d H:i:s'));
                    $self->assertEquals('2016-12-02 17:00:00', $object->getFinishedAt()->format('Y-m-d H:i:s'));
                }

                if ($object instanceof Timephase) {
                    $self->assertEquals(1, $object->getType());
                    $self->assertEquals(1, $object->getUnit());
                    $self->assertEquals('PT0H3M0S', $object->getValue());
                    $self->assertEquals('2016-11-07 08:00:00', $object->getStartedAt()->format('Y-m-d H:i:s'));
                    $self->assertEquals('2016-11-08 08:00:00', $object->getFinishedAt()->format('Y-m-d H:i:s'));
                    $self->assertEquals('2015-12-13 07:43:00', $object->getAssignment()->getCreatedAt()->format('Y-m-d H:i:s'));
                    $self->assertEquals('2016-11-07 08:00:00', $object->getAssignment()->getStartedAt()->format('Y-m-d H:i:s'));
                }
            })
        ;

        $this->files = $this->finder->files()->in($this->path)->name(self::IMPORT_ASSIGNMENTS);
        foreach ($this->files as $file) {
            $content = file_get_contents($file->getRealPath());
            $this->importService->importProjects($content);
        }
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->importService = null;
        $this->container = null;
        $this->entityManager = null;
        $this->path = null;
        $this->files = null;
        $this->finder = null;
    }
}
