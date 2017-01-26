<?php

namespace AppBundle\Tests\Services;

use AppBundle\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\RequestStack;

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

        $this->userService = new UserService($requestStack, $domain);
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
