<?php

namespace AppBundle\Tests\Services;

use AppBundle\Services\UserService;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class UserServiceTest extends KernelTestCase
{
    /** @var UserService */
    private $userService;

    public function setUp()
    {
        self::bootKernel();

        $this->container = self::$kernel->getContainer();
        $domain = $this->container->getParameter('domain');
        $requestStack = $this
            ->getMockBuilder(RequestStack::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $em = $this
            ->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $userPassEncoder = $this
            ->getMockBuilder(UserPasswordEncoder::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $router = $this
            ->getMockBuilder(Router::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->userService = new UserService($requestStack, $domain, $em, $userPassEncoder, $router);
    }

    public function testGenerateActivationResetToken()
    {
        $this->assertTrue(is_string($this->userService->generateActivationResetToken()), 'Reset token is not string');
    }

    public function tearDown()
    {
        $this->userService = null;
    }
}
