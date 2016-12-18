<?php

namespace MainBundle\Tests\Controller;

use AppBundle\Entity\User;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends BaseController
{
    public function testFormIsDisplayedOnRegisterPage()
    {
        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/user/register');

        $this->assertContains('id="register_username"', $crawler->html());
        $this->assertContains('name="register[username]"', $crawler->html());
        $this->assertContains('id="register_email"', $crawler->html());
        $this->assertContains('name="register[email]"', $crawler->html());
        $this->assertContains('id="register_plainPassword_first"', $crawler->html());
        $this->assertContains('name="register[plainPassword][first]"', $crawler->html());
        $this->assertContains('id="register_plainPassword_second"', $crawler->html());
        $this->assertContains('name="register[plainPassword][second]"', $crawler->html());
        $this->assertContains('id="register_firstName"', $crawler->html());
        $this->assertContains('name="register[firstName]"', $crawler->html());
        $this->assertContains('id="register_lastName"', $crawler->html());
        $this->assertContains('name="register[lastName]"', $crawler->html());
        $this->assertContains('id="register_phone"', $crawler->html());
        $this->assertContains('name="register[phone]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnRegisterPage()
    {
        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/user/register');

        $form = $crawler->filter('#register-form')->first()->form();

        $crawler = $this->client->submit($form);

        $this->assertContains('The username field should not be blank', $crawler->html());
        $this->assertContains('The email field should not be blank', $crawler->html());
        $this->assertContains('Password fields should not be blank', $crawler->html());
        $this->assertContains('The first name field should not be blank', $crawler->html());
        $this->assertContains('The last name field should not be blank', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testUsernameIsUniqueOnRegisterPage()
    {
        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/user/register');

        $form = $crawler->filter('#register-form')->first()->form();
        $form['register[username]'] = 'admin';
        $form['register[email]'] = 'testuser2@trisoft.ro';
        $form['register[plainPassword][first]'] = 'Password2';
        $form['register[plainPassword][second]'] = 'Password2';
        $form['register[firstName]'] = 'FirstName';
        $form['register[lastName]'] = 'LastName';

        $crawler = $this->client->submit($form);

        $this->assertContains('The username is already used', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEmailIsUniqueOnRegisterPage()
    {
        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/user/register');

        $form = $crawler->filter('#register-form')->first()->form();
        $form['register[username]'] = 'testuser2';
        $form['register[email]'] = 'admin@trisoft.ro';
        $form['register[plainPassword][first]'] = 'Password2';
        $form['register[plainPassword][second]'] = 'Password2';
        $form['register[firstName]'] = 'FirstName';
        $form['register[lastName]'] = 'LastName';

        $crawler = $this->client->submit($form);

        $this->assertContains('The email is already used', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testPasswordPatternOnRegisterPage()
    {
        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/user/register');

        $form = $crawler->filter('#register-form')->first()->form();
        $form['register[username]'] = 'testuser';
        $form['register[email]'] = 'testuser@trisoft.ro';
        $form['register[plainPassword][first]'] = 'password';
        $form['register[plainPassword][second]'] = 'password';
        $form['register[firstName]'] = 'FirstName';
        $form['register[lastName]'] = 'LastName';

        $crawler = $this->client->submit($form);

        $this->assertContains('Password must contain 1 upper case letter, 1 lower case letter, and 1 number', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testPasswordsMatchOnRegisterPage()
    {
        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/user/register');

        $form = $crawler->filter('#register-form')->first()->form();
        $form['register[username]'] = 'testuser';
        $form['register[email]'] = 'testuser@trisoft.ro';
        $form['register[plainPassword][first]'] = 'Password1';
        $form['register[plainPassword][second]'] = 'Password2';
        $form['register[firstName]'] = 'FirstName';
        $form['register[lastName]'] = 'LastName';

        $crawler = $this->client->submit($form);

        $this->assertContains('The password fields do not match', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testRegisterSuccessfully()
    {
        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/user/register');

        $form = $crawler->filter('#register-form')->first()->form();
        $form['register[username]'] = 'testuser';
        $form['register[email]'] = 'testuser@trisoft.ro';
        $form['register[plainPassword][first]'] = 'Password1';
        $form['register[plainPassword][second]'] = 'Password1';
        $form['register[firstName]'] = 'FirstName';
        $form['register[lastName]'] = 'LastName';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $target = $this->client->getResponse()->getTargetUrl();

        $this->client->followRedirect();
        $this->assertEquals('/', $target);

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $user = $this
            ->em
            ->getRepository(User::class)
            ->findOneBy([
                'email' => 'testuser@trisoft.ro',

            ])
        ;
        $this->em->remove($user);
        $this->em->flush();
    }

    public function testRegisterActivationTokenExpired()
    {
        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/user/register');

        $form = $crawler->filter('#register-form')->first()->form();
        $form['register[username]'] = 'testuser';
        $form['register[email]'] = 'testuser@trisoft.ro';
        $form['register[plainPassword][first]'] = 'Password1';
        $form['register[plainPassword][second]'] = 'Password1';
        $form['register[firstName]'] = 'FirstName';
        $form['register[lastName]'] = 'LastName';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $target = $this->client->getResponse()->getTargetUrl();

        $this->client->followRedirect();
        $this->assertEquals('/', $target);

        $user = $this
            ->em
            ->getRepository(User::class)
            ->findOneBy([
                'email' => 'testuser@trisoft.ro',
            ])
        ;
        $user->setActivationTokenCreatedAt(new \DateTime('-3 day'));
        $this->em->persist($user);
        $this->em->flush();

        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/user/activate/%s', $user->getActivationToken()));
        $this->assertContains('Activation token has expired!', $crawler->html());
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $user = $this
            ->em
            ->getRepository(User::class)
            ->findOneBy([
                'email' => 'testuser@trisoft.ro',

            ])
        ;
        $this->em->remove($user);
        $this->em->flush();
    }

    public function testRegisterResendActivationToken()
    {
        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/user/register');

        $form = $crawler->filter('#register-form')->first()->form();
        $form['register[username]'] = 'testuser';
        $form['register[email]'] = 'testuser@trisoft.ro';
        $form['register[plainPassword][first]'] = 'Password1';
        $form['register[plainPassword][second]'] = 'Password1';
        $form['register[firstName]'] = 'FirstName';
        $form['register[lastName]'] = 'LastName';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $target = $this->client->getResponse()->getTargetUrl();

        $this->client->followRedirect();
        $this->assertEquals('/', $target);

        $user = $this
            ->em
            ->getRepository(User::class)
            ->findOneBy([
                'email' => 'testuser@trisoft.ro',
            ])
        ;
        $user->setActivationTokenCreatedAt(new \DateTime('-3 day'));
        $this->em->persist($user);
        $this->em->flush();

        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/user/activate/%s', $user->getActivationToken()));

        $this->assertContains('Activation token has expired!', $crawler->html());

        $link = $crawler->selectLink('Click here to resend the activation token')->link();
        $this->client->click($link);
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $target = $this->client->getResponse()->getTargetUrl();

        $this->client->followRedirect();
        $this->assertEquals('/', $target);

        $user = $this
            ->em
            ->getRepository(User::class)
            ->findOneBy([
                'email' => 'testuser@trisoft.ro',

            ])
        ;
        $this->em->remove($user);
        $this->em->flush();
    }

    public function testRegisterActivationSuccessfully()
    {
        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/user/register');

        $form = $crawler->filter('#register-form')->first()->form();
        $form['register[username]'] = 'testuser';
        $form['register[email]'] = 'testuser@trisoft.ro';
        $form['register[plainPassword][first]'] = 'Password1';
        $form['register[plainPassword][second]'] = 'Password1';
        $form['register[firstName]'] = 'FirstName';
        $form['register[lastName]'] = 'LastName';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $target = $this->client->getResponse()->getTargetUrl();

        $this->client->followRedirect();
        $this->assertEquals('/', $target);

        $user = $this
            ->em
            ->getRepository(User::class)
            ->findOneBy([
                'email' => 'testuser@trisoft.ro',
            ])
        ;

        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/user/activate/%s', $user->getActivationToken()));
        $this->assertContains('Your account has been activated!', $crawler->html());
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $user = $this
            ->em
            ->getRepository(User::class)
            ->findOneBy([
                'email' => 'testuser@trisoft.ro',

            ])
        ;
        $this->em->remove($user);
        $this->em->flush();
    }

    public function testFormIsDisplayedOnRequestResetPasswordPage()
    {
        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/user/request-reset');

        $this->assertContains('id="reset_password_email"', $crawler->html());
        $this->assertContains('name="reset_password[email]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnRequestResetPasswordPage()
    {
        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/user/request-reset');

        $form = $crawler->filter('#reset-password-form')->first()->form();

        $crawler = $this->client->submit($form);

        $this->assertContains('The email field should not be blank', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEmailNotFoundOnRequestResetPasswordPage()
    {
        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/user/request-reset');

        $form = $crawler->filter('#reset-password-form')->first()->form();
        $form['reset_password[email]'] = 'noreply@trisoft.ro';

        $crawler = $this->client->submit($form);

        $this->assertContains('Requested user email not found', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormSuccessfullySubmittedOnRequestResetPasswordPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/user/request-reset');

        $form = $crawler->filter('#reset-password-form')->first()->form();
        $form['reset_password[email]'] = 'testuser@trisoft.ro';

        $this->client->submit($form);
        $target = $this->client->getResponse()->getTargetUrl();

        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertEquals('/', $target);

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormIsDisplayedOnResetPasswordPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/user/request-reset');

        $form = $crawler->filter('#reset-password-form')->first()->form();
        $form['reset_password[email]'] = 'testuser@trisoft.ro';

        $this->client->submit($form);
        $target = $this->client->getResponse()->getTargetUrl();

        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->assertEquals('/', $target);

        $user = $this
            ->em
            ->getRepository(User::class)
            ->findOneBy([
                'email' => 'testuser@trisoft.ro',
            ])
        ;

        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/user/reset-password/%s', $user->getResetPasswordToken()));

        $this->assertContains('id="change_password_plainPassword_first"', $crawler->html());
        $this->assertContains('name="change_password[plainPassword][first]"', $crawler->html());
        $this->assertContains('id="change_password_plainPassword_second"', $crawler->html());
        $this->assertContains('name="change_password[plainPassword][second]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testPasswordEmptyOnResetPasswordPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/user/request-reset');

        $form = $crawler->filter('#reset-password-form')->first()->form();
        $form['reset_password[email]'] = 'testuser@trisoft.ro';

        $this->client->submit($form);
        $target = $this->client->getResponse()->getTargetUrl();

        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->assertEquals('/', $target);

        $user = $this
            ->em
            ->getRepository(User::class)
            ->findOneBy([
                'email' => 'testuser@trisoft.ro',
            ])
        ;

        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/user/reset-password/%s', $user->getResetPasswordToken()));

        $form = $crawler->filter('#reset-password-form')->first()->form();
        $crawler = $this->client->submit($form);

        $this->assertContains('Password fields should not be blank', $crawler->html());
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testPasswordsMatchOnResetPasswordPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/user/request-reset');

        $form = $crawler->filter('#reset-password-form')->first()->form();
        $form['reset_password[email]'] = 'testuser@trisoft.ro';

        $this->client->submit($form);
        $target = $this->client->getResponse()->getTargetUrl();

        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->assertEquals('/', $target);

        $user = $this
            ->em
            ->getRepository(User::class)
            ->findOneBy([
                'email' => 'testuser@trisoft.ro',
            ])
        ;

        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/user/reset-password/%s', $user->getResetPasswordToken()));

        $form = $crawler->filter('#reset-password-form')->first()->form();
        $form['change_password[plainPassword][first]'] = 'Password1';
        $form['change_password[plainPassword][second]'] = 'Password2';
        $crawler = $this->client->submit($form);

        $this->assertContains('The password fields do not match', $crawler->html());
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testPasswordPatternOnResetPasswordPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/user/request-reset');

        $form = $crawler->filter('#reset-password-form')->first()->form();
        $form['reset_password[email]'] = 'testuser@trisoft.ro';

        $this->client->submit($form);
        $target = $this->client->getResponse()->getTargetUrl();

        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->assertEquals('/', $target);

        $user = $this
            ->em
            ->getRepository(User::class)
            ->findOneBy([
                'email' => 'testuser@trisoft.ro',
            ])
        ;

        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/user/reset-password/%s', $user->getResetPasswordToken()));

        $form = $crawler->filter('#reset-password-form')->first()->form();
        $form['change_password[plainPassword][first]'] = 'password';
        $form['change_password[plainPassword][second]'] = 'password';
        $crawler = $this->client->submit($form);

        $this->assertContains('Password must contain 1 upper case letter, 1 lower case letter, and 1 number', $crawler->html());
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testResetPasswordSuccessfully()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/user/request-reset');

        $form = $crawler->filter('#reset-password-form')->first()->form();
        $form['reset_password[email]'] = 'testuser@trisoft.ro';

        $this->client->submit($form);
        $target = $this->client->getResponse()->getTargetUrl();

        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->assertEquals('/', $target);

        $user = $this
            ->em
            ->getRepository(User::class)
            ->findOneBy([
                'email' => 'testuser@trisoft.ro',
            ])
        ;

        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/user/reset-password/%s', $user->getResetPasswordToken()));

        $form = $crawler->filter('#reset-password-form')->first()->form();
        $form['change_password[plainPassword][first]'] = 'Password1';
        $form['change_password[plainPassword][second]'] = 'Password1';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $target = $this->client->getResponse()->getTargetUrl();

        $this->client->followRedirect();
        $this->assertEquals('/login', $target);

        $this->assertContains('id="login-form"', $this->client->getResponse()->getContent());
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
