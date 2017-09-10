<?php

namespace AppBundle\Tests\Controller\Admin;

use AppBundle\Entity\Rasci;
use AppBundle\Entity\WorkPackage;
use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RasciControllerTest extends BaseController
{
    public function testFormIsDisplayedOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/rasci/create');

        $this->assertContains('id="create_workPackage"', $crawler->html());
        $this->assertContains('name="create[workPackage]"', $crawler->html());
        $this->assertContains('id="create_user"', $crawler->html());
        $this->assertContains('name="create[user]"', $crawler->html());
        $this->assertContains('id="create_data"', $crawler->html());
        $this->assertContains('name="create[data]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/rasci/create');

        $form = $crawler->filter('#create-form')->first()->form();

        $crawler = $this->client->submit($form);

        $this->assertContains('The data field should not be blank', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $workPackage = (new WorkPackage())
            ->setPuid('1')
            ->setType(WorkPackage::TYPE_PHASE)
            ->setName('workpackage10')
            ->setDuration(0)
        ;
        $this->em->persist($workPackage);
        $this->em->flush();

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/rasci/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['create[workPackage]'] = $workPackage->getId();
        $form['create[user]'] = $this->user->getId();
        $form['create[data]'] = 'data3';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Rasci successfully created!', $this->client->getResponse()->getContent());

        $rasci = $this
            ->em
            ->getRepository(Rasci::class)
            ->findOneBy([
                'data' => 'data3',
            ])
        ;
        $this->em->remove($rasci);
    }

    public function testDeleteAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $workPackage = (new WorkPackage())
            ->setType(WorkPackage::TYPE_PHASE)
            ->setPuid('2')
            ->setName('workpackage11')
            ->setDuration(0)
        ;
        $this->em->persist($workPackage);

        $rasci = (new Rasci())
            ->setWorkPackage($workPackage)
            ->setUser($this->user)
            ->setData('data4')
        ;
        $this->em->persist($rasci);
        $this->em->flush();

        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/admin/rasci/%d/edit', $rasci->getId()));

        $link = $crawler->selectLink('Delete')->link();
        $this->client->click($link);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Rasci successfully deleted!', $this->client->getResponse()->getContent());
    }

    public function testFormIsDisplayedOnEditPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/rasci/1/edit');

        $this->assertContains('id="create_workPackage"', $crawler->html());
        $this->assertContains('name="create[workPackage]"', $crawler->html());
        $this->assertContains('id="create_user"', $crawler->html());
        $this->assertContains('name="create[user]"', $crawler->html());
        $this->assertContains('id="create_data"', $crawler->html());
        $this->assertContains('name="create[data]"', $crawler->html());
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
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/rasci/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[data]'] = '';

        $crawler = $this->client->submit($form);

        $this->assertContains('The data field should not be blank', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEditAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/rasci/2/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[data]'] = 'data2';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Rasci successfully edited!', $this->client->getResponse()->getContent());
    }

    public function testDataTableOnListPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/rasci/list');

        $this->assertEquals(1, $crawler->filter('#data-table-command')->count());
        $this->assertContains('data-column-id="id"', $crawler->html());
        $this->assertContains('data-column-id="workPackageName"', $crawler->html());
        $this->assertContains('data-column-id="userFullName"', $crawler->html());
        $this->assertContains('data-column-id="commands"', $crawler->html());
        $this->assertEquals(1, $crawler->filter('.zmdi-plus')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testTableStructureOnShowAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/rasci/1/show');

        $this->assertEquals(1, $crawler->filter('.dropdown-menu-right')->count());
        $this->assertEquals(1, $crawler->filter('.table-responsive')->count());
        $this->assertEquals(1, $crawler->filter('.table-striped')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
