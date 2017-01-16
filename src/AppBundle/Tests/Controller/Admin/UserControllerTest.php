<?php

namespace AppBundle\Tests\Controller\Admin;

use AppBundle\Entity\User;
use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends BaseController
{
    public function testFormIsDisplayedOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/user/create');

        $this->assertContains('id="create_username"', $crawler->html());
        $this->assertContains('name="create[username]"', $crawler->html());
        $this->assertContains('id="create_email"', $crawler->html());
        $this->assertContains('name="create[email]"', $crawler->html());
        $this->assertContains('id="create_plainPassword_first"', $crawler->html());
        $this->assertContains('name="create[plainPassword][first]"', $crawler->html());
        $this->assertContains('id="create_plainPassword_second"', $crawler->html());
        $this->assertContains('name="create[plainPassword][second]"', $crawler->html());
        $this->assertContains('id="create_firstName"', $crawler->html());
        $this->assertContains('name="create[firstName]"', $crawler->html());
        $this->assertContains('id="create_lastName"', $crawler->html());
        $this->assertContains('name="create[lastName]"', $crawler->html());
        $this->assertContains('id="create_phone"', $crawler->html());
        $this->assertContains('name="create[phone]"', $crawler->html());
        $this->assertContains('id="create_avatarFile_file"', $crawler->html());
        $this->assertContains('name="create[avatarFile][file]"', $crawler->html());
        $this->assertContains('id="create_roles_0"', $crawler->html());
        $this->assertContains('id="create_roles_1"', $crawler->html());
        $this->assertContains('id="create_roles_2"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/user/create');

        $form = $crawler->filter('#create-form')->first()->form();

        $crawler = $this->client->submit($form);

        $this->assertContains('The username field should not be blank', $crawler->html());
        $this->assertContains('The email field should not be blank', $crawler->html());
        $this->assertContains('Password fields should not be blank', $crawler->html());
        $this->assertContains('The first name field should not be blank', $crawler->html());
        $this->assertContains('The last name field should not be blank', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testUsernameIsUniqueOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/user/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['create[username]'] = 'testuser';
        $form['create[email]'] = 'testuser2@trisoft.ro';
        $form['create[plainPassword][first]'] = 'Password2';
        $form['create[plainPassword][second]'] = 'Password2';
        $form['create[firstName]'] = 'FirstName';
        $form['create[lastName]'] = 'LastName';

        $crawler = $this->client->submit($form);

        $this->assertContains('The username is already used', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEmailIsUniqueOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/user/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['create[username]'] = 'testuser2';
        $form['create[email]'] = 'testuser@trisoft.ro';
        $form['create[plainPassword][first]'] = 'Password2';
        $form['create[plainPassword][second]'] = 'Password2';
        $form['create[firstName]'] = 'FirstName';
        $form['create[lastName]'] = 'LastName';

        $crawler = $this->client->submit($form);

        $this->assertContains('The email is already used', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testPasswordPatternOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/user/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['create[username]'] = 'testuser2';
        $form['create[email]'] = 'testuser2@trisoft.ro';
        $form['create[plainPassword][first]'] = 'password';
        $form['create[plainPassword][second]'] = 'password';
        $form['create[firstName]'] = 'FirstName';
        $form['create[lastName]'] = 'LastName';

        $crawler = $this->client->submit($form);

        $this->assertContains('Password must contain 1 upper case letter, 1 lower case letter, and 1 number', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testPasswordsMatchOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/user/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['create[username]'] = 'testuser2';
        $form['create[email]'] = 'testuser2@trisoft.ro';
        $form['create[plainPassword][first]'] = 'Password1';
        $form['create[plainPassword][second]'] = 'Password2';
        $form['create[firstName]'] = 'FirstName';
        $form['create[lastName]'] = 'LastName';

        $crawler = $this->client->submit($form);

        $this->assertContains('The password fields do not match', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/user/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['create[username]'] = 'testuser2';
        $form['create[email]'] = 'testuser2@trisoft.ro';
        $form['create[plainPassword][first]'] = 'Password2';
        $form['create[plainPassword][second]'] = 'Password2';
        $form['create[firstName]'] = 'FirstName';
        $form['create[lastName]'] = 'LastName';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('User successfully created!', $this->client->getResponse()->getContent());

        $user = $this
            ->em
            ->getRepository(User::class)
            ->findOneBy([
                'email' => 'testuser2@trisoft.ro',
            ])
        ;
        $this->em->remove($user);
        $this->em->flush();
    }

    public function testDeleteAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $roles = ['ROLE_USER'];
        $user = new User();

        $encoder = $this
            ->client
            ->getContainer()
            ->get('security.encoder_factory')
            ->getEncoder($user)
        ;

        $user->setUsername('testuser2')
            ->setEmail('testuser2@trisoft.ro')
            ->setPassword($encoder->encodePassword('Password2', $user->getSalt()))
            ->setFirstName('FirstName')
            ->setLastName('LastName')
            ->setRoles($roles)
            ->setIsEnabled(true)
            ->setIsSuspended(false)
        ;
        $this->em->persist($user);
        $this->em->flush();

        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/admin/user/%d/edit', $user->getId()));

        $link = $crawler->selectLink('Delete')->link();
        $this->client->click($link);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('User successfully deleted!', $this->client->getResponse()->getContent());
    }

    public function testFormIsDisplayedOnEditPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/user/2/edit');

        $this->assertContains('id="edit_firstName"', $crawler->html());
        $this->assertContains('name="edit[firstName]"', $crawler->html());
        $this->assertContains('id="edit_lastName"', $crawler->html());
        $this->assertContains('name="edit[lastName]"', $crawler->html());
        $this->assertContains('id="edit_avatarFile_file"', $crawler->html());
        $this->assertContains('name="edit[avatarFile][file]"', $crawler->html());
        $this->assertContains('id="edit_phone"', $crawler->html());
        $this->assertContains('name="edit[phone]"', $crawler->html());
        $this->assertContains('id="edit_roles_0"', $crawler->html());
        $this->assertContains('id="edit_roles_1"', $crawler->html());
        $this->assertContains('id="edit_roles_2"', $crawler->html());
        $this->assertContains('id="edit_isEnabled"', $crawler->html());
        $this->assertContains('name="edit[isEnabled]"', $crawler->html());
        $this->assertContains('id="edit_isSuspended"', $crawler->html());
        $this->assertContains('name="edit[isSuspended]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());
        $this->assertContains('class="zmdi zmdi-delete"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnEditPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/user/2/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['edit[firstName]'] = '';
        $form['edit[lastName]'] = '';
        $crawler = $this->client->submit($form);

        $this->assertContains('The first name field should not be blank', $crawler->html());
        $this->assertContains('The last name field should not be blank', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEditAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $roles = ['ROLE_USER'];
        $user = new User();

        $encoder = $this
            ->client
            ->getContainer()
            ->get('security.encoder_factory')
            ->getEncoder($user)
        ;

        $user->setUsername('testuser2')
            ->setEmail('testuser2@trisoft.ro')
            ->setPassword($encoder->encodePassword('Password2', $user->getSalt()))
            ->setFirstName('FirstName')
            ->setLastName('LastName')
            ->setRoles($roles)
            ->setIsEnabled(true)
            ->setIsSuspended(false)
        ;
        $this->em->persist($user);
        $this->em->flush();

        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/admin/user/%d/edit', $user->getId()));

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['edit[firstName]'] = 'FirstName';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('User successfully edited!', $this->client->getResponse()->getContent());

        $user = $this
            ->em
            ->getRepository(User::class)
            ->findOneBy([
                'email' => 'testuser2@trisoft.ro',
            ])
        ;
        $this->em->remove($user);
        $this->em->flush();
    }

    public function testDataTableOnListPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/user/list');

        $this->assertEquals(1, $crawler->filter('#data-table-command')->count());
        $this->assertContains('data-column-id="id"', $crawler->html());
        $this->assertContains('data-column-id="username"', $crawler->html());
        $this->assertContains('data-column-id="email"', $crawler->html());
        $this->assertContains('data-column-id="roles"', $crawler->html());
        $this->assertContains('data-column-id="commands"', $crawler->html());
        $this->assertEquals(1, $crawler->filter('.zmdi-plus')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testTableStructureOnShowAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/user/1/show');

        $this->assertEquals(1, $crawler->filter('.dropdown-menu-right')->count());
        $this->assertEquals(1, $crawler->filter('.table-responsive')->count());
        $this->assertEquals(1, $crawler->filter('.table-striped')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
