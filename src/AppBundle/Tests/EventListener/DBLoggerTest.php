<?php

namespace AppBundle\Tests\EventListener;

use AppBundle\Entity\Log;
use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectCategory;
use AppBundle\Entity\User;
use AppBundle\EventListener\DBLogger;
use AppBundle\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\UnitOfWork;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class DBLoggerTest extends \PHPUnit_Framework_TestCase
{
    /** @var TokenStorage */
    private $tokenStorage;

    /** @var DBLogger */
    private $dbLogger;

    protected function setUp()
    {
        $this->tokenStorage = $this
            ->getMockBuilder(TokenStorage::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->dbLogger = new DBLogger($this->tokenStorage);
    }

    /**
     * @dataProvider getDataForTestOnFlush
     *
     * @param $entities
     */
    public function testOnFlush($authenticated, $entities)
    {
        $self = $this;

        $event = $this
            ->getMockBuilder(OnFlushEventArgs::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $em = $this
            ->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $uok = $this
            ->getMockBuilder(UnitOfWork::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $metadata = $this
            ->getMockBuilder(ClassMetadata::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $changedSet = [
            'name' => [
                'project-category-1',
                'project-category-2',
            ],
            'sequence' => [
                1,
                2,
            ],
            'project' => [
                (new Project())
                    ->setName('project-test-1')
                    ->setNumber('project-number-1'),
                (new Project())
                    ->setName('project-test-2')
                    ->setNumber('project-test-2'),
            ],
        ];
        if ($authenticated) {
            $tokenInterface = $this
                ->getMockBuilder(TokenInterface::class)
                ->getMock()
            ;
            $user = $this
                ->getMockBuilder(User::class)
                ->getMock()
            ;
            $repository = $this
                ->getMockBuilder(UserRepository::class)
                ->disableOriginalConstructor()
                ->getMock()
            ;

            $this
                ->tokenStorage
                ->expects($this->once())
                ->method('getToken')
                ->willReturn($tokenInterface)
            ;
            $tokenInterface
                ->expects($this->once())
                ->method('getUser')
                ->willReturn($user)
            ;
            $em
                ->expects($this->once())
                ->method('getRepository')
                ->willReturn($repository)
            ;
            $user
                ->expects($this->once())
                ->method('getId')
                ->willReturn(1)
            ;
            $repository
                ->expects($this->once())
                ->method('find')
                ->willReturn(new User())
            ;
        }

        $event
            ->expects($this->once())
            ->method('getEntityManager')
            ->willReturn($em)
        ;
        $em
            ->expects($this->once())
            ->method('getUnitOfWork')
            ->willReturn($uok)
        ;
        $uok
            ->expects($this->once())
            ->method('getScheduledEntityUpdates')
            ->willReturn($entities)
        ;
        $em
            ->expects($this->atLeastOnce())
            ->method('getClassMetadata')
            ->willReturnMap([
                [Log::class, $metadata],
                [
                    Project::class,
                    (new Project())
                        ->setName('project-test-2')
                        ->setNumber('project-number-2'),
                ],
            ])
        ;
        $uok
            ->expects($this->atLeastOnce())
            ->method('getEntityChangeSet')
            ->willReturn($changedSet)
        ;
        $uok
            ->expects($this->once())
            ->method('persist')
            ->willReturnCallback(function ($log) use ($self, $authenticated) {
                $oldValues = $log->getOldValue();
                $newValues = $log->getNewValue();

                $self->assertEquals('project-category-1', $oldValues['name']);
                $self->assertEquals(1, $oldValues['sequence']);
                $self->assertEquals('project-category-2', $newValues['name']);
                $self->assertEquals(2, $newValues['sequence']);
                $self->assertEquals('project-test-2', $newValues['project'][0]);
                $self->assertEquals(null, $newValues['project'][1]);

                if ($authenticated) {
                    $self->assertEquals(User::class, get_class($log->getUser()));
                }
            })
        ;
        $uok
            ->expects($this->once())
            ->method('computeChangeSet')
            ->willReturnCallback(function ($a, $b) use ($self, $metadata) {
                $self->assertEquals(get_class($metadata), get_class($a));
                $self->assertEquals(Log::class, get_class($b));
            });

        $this->dbLogger->onFlush($event);
    }

    /**
     * @return array
     */
    public function getDataForTestOnFlush()
    {
        return [
            [
                true,
                [
                    (new ProjectCategory())
                        ->setName('project-category-1')
                        ->setSequence(1),
                ],
            ],
            [
                false,
                [
                    (new ProjectCategory())
                        ->setName('project-category-1')
                        ->setSequence(1),
                ],
            ],
        ];
    }
}
