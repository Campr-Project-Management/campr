<?php

namespace AppBundle\Tests\Controller\Admin;

use AppBundle\Entity\Assignment;
use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AssignmentControllerTest extends BaseController
{
    public function testFormIsDisplayedOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/assignment/create');

        $this->assertContains('id="create_workPackage"', $crawler->html());
        $this->assertContains('name="create[workPackage]"', $crawler->html());
        $this->assertContains('id="create_workPackageProjectWorkCostType"', $crawler->html());
        $this->assertContains('name="create[workPackageProjectWorkCostType]"', $crawler->html());
        $this->assertContains('id="create_milestone"', $crawler->html());
        $this->assertContains('name="create[milestone]"', $crawler->html());
        $this->assertContains('id="create_percentWorkComplete"', $crawler->html());
        $this->assertContains('name="create[percentWorkComplete]"', $crawler->html());
        $this->assertContains('id="create_startedAt"', $crawler->html());
        $this->assertContains('name="create[startedAt]"', $crawler->html());
        $this->assertContains('id="create_finishedAt"', $crawler->html());
        $this->assertContains('name="create[finishedAt]"', $crawler->html());
        $this->assertContains('id="create_confirmed"', $crawler->html());
        $this->assertContains('name="create[confirmed]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/assignment/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['create[percentWorkComplete]'] = '';

        $crawler = $this->client->submit($form);

        $this->assertContains('The milestone field should not be blank', $crawler->html());
        $this->assertContains('The work complete percent field should not be blank', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testMilestoneIsNumericOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/assignment/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['create[milestone]'] = '-2';

        $crawler = $this->client->submit($form);

        $this->assertContains('The milestone field should contain numbers greater than or equal to 0', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testPercentWorkCompleteIsNumericOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/assignment/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['create[milestone]'] = '0';
        $form['create[percentWorkComplete]'] = '-2';

        $crawler = $this->client->submit($form);

        $this->assertContains('The work percent field should contain numbers greater than or equal to 0', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/assignment/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['create[milestone]'] = '3';
        $form['create[percentWorkComplete]'] = '3';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Assignment successfully created!', $this->client->getResponse()->getContent());

        $assignment = $this
            ->em
            ->getRepository(Assignment::class)
            ->findOneBy([
                'milestone' => '3',
                'percentWorkComplete' => '3',
            ])
        ;
        $this->em->remove($assignment);
        $this->em->flush();
    }

    public function testDeleteAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $assignment = (new Assignment())
            ->setMilestone(4)
            ->setPercentWorkComplete(4)
        ;
        $this->em->persist($assignment);
        $this->em->flush();

        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/admin/assignment/%d/edit', $assignment->getId()));

        $link = $crawler->selectLink('Delete')->link();
        $this->client->click($link);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Assignment successfully deleted!', $this->client->getResponse()->getContent());
    }

    public function testFormIsDisplayedOnEditPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/assignment/1/edit');

        $this->assertContains('id="create_workPackage"', $crawler->html());
        $this->assertContains('name="create[workPackage]"', $crawler->html());
        $this->assertContains('id="create_workPackageProjectWorkCostType"', $crawler->html());
        $this->assertContains('name="create[workPackageProjectWorkCostType]"', $crawler->html());
        $this->assertContains('id="create_milestone"', $crawler->html());
        $this->assertContains('name="create[milestone]"', $crawler->html());
        $this->assertContains('id="create_percentWorkComplete"', $crawler->html());
        $this->assertContains('name="create[percentWorkComplete]"', $crawler->html());
        $this->assertContains('id="create_startedAt"', $crawler->html());
        $this->assertContains('name="create[startedAt]"', $crawler->html());
        $this->assertContains('id="create_finishedAt"', $crawler->html());
        $this->assertContains('name="create[finishedAt]"', $crawler->html());
        $this->assertContains('id="create_confirmed"', $crawler->html());
        $this->assertContains('name="create[confirmed]"', $crawler->html());
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
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/assignment/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[milestone]'] = '';
        $form['create[percentWorkComplete]'] = '';

        $crawler = $this->client->submit($form);

        $this->assertContains('The milestone field should not be blank', $crawler->html());
        $this->assertContains('The work complete percent field should not be blank', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testMilestoneIsNumericOnEditPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/assignment/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[milestone]'] = '-2';

        $crawler = $this->client->submit($form);

        $this->assertContains('The milestone field should contain numbers greater than or equal to 0', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testPercentWorkCompleteIsNumericOnEditPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/assignment/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[percentWorkComplete]'] = '-2';

        $crawler = $this->client->submit($form);

        $this->assertContains('The work percent field should contain numbers greater than or equal to 0', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEditAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/assignment/2/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[milestone]'] = '2';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Assignment successfully edited!', $this->client->getResponse()->getContent());
    }

    public function testDataTableOnListPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/assignment/list');

        $this->assertEquals(1, $crawler->filter('#data-table-command')->count());
        $this->assertContains('data-column-id="id"', $crawler->html());
        $this->assertContains('data-column-id="workPackageName"', $crawler->html());
        $this->assertContains('data-column-id="workPackageProjectWorkCostTypeName"', $crawler->html());
        $this->assertContains('data-column-id="milestone"', $crawler->html());
        $this->assertContains('data-column-id="percentWorkComplete"', $crawler->html());
        $this->assertContains('data-column-id="commands"', $crawler->html());
        $this->assertEquals(1, $crawler->filter('.zmdi-plus')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testTableStructureOnShowAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/assignment/1/show');

        $this->assertEquals(1, $crawler->filter('.dropdown-menu-right')->count());
        $this->assertEquals(1, $crawler->filter('.table-responsive')->count());
        $this->assertEquals(1, $crawler->filter('.table-striped')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
