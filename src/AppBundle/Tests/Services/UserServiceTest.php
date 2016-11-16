<?php

namespace AppBundle\Tests\Services;

use AppBundle\Services\UserService;

class UserServiceTest extends \PHPUnit_Framework_TestCase
{
    /** @var UserService */
    private $userService;

    public function setUp()
    {
        $this->userService = new UserService();
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
