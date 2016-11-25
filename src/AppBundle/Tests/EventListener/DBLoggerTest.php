<?php

namespace AppBundle\Tests\EventListener;

use AppBundle\Entity\Assignment;
use AppBundle\Entity\Note;
use AppBundle\Entity\Timephase;
use AppBundle\EventListener\DBLogger;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Mapping\PostPersist;
use Doctrine\ORM\UnitOfWork;
use Symfony\Component\HttpKernel\Tests\KernelTest;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class DBLoggerTest extends KernelTest
{
    private $dbLogger;

    protected function setUp()
    {
        $tokenStorage = $this
            ->getMockBuilder(TokenStorage::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $this->dbLogger = new DBLogger($tokenStorage);
    }

    /**
     * @dataProvider getDataForTestOnFlush
     *
     * @param Object $value
     */
    public function testOnFlush($entities)
    {
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

        $this->dbLogger->onFlush($event);
    }

    /**
     * @return array
     */
    public function getDataForTestOnFlush()
    {
        return [
            [
                (new Note())
                    ->setTitle("note"),
                (new Assignment())
                    ->setMilestone(6)
                    ->addTimephase(
                        (new Timephase())
                            ->setType(1)
                    ),
            ],
        ];
    }
}
