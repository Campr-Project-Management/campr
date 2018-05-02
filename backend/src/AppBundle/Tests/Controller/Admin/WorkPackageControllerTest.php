<?php

namespace AppBundle\Tests\Controller\Admin;

use AppBundle\Entity\WorkPackage;
use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WorkPackageControllerTest extends BaseController
{
    public function testFormIsDisplayedOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/workpackage/create');

        $this->assertContains('id="create_name"', $crawler->html());
        $this->assertContains('name="create[name]"', $crawler->html());
        $this->assertContains('id="create_type"', $crawler->html());
        $this->assertContains('name="create[type]"', $crawler->html());
        $this->assertContains('id="create_workPackageStatus"', $crawler->html());
        $this->assertContains('name="create[workPackageStatus]"', $crawler->html());
        $this->assertContains('id="create_progress"', $crawler->html());
        $this->assertContains('name="create[progress]"', $crawler->html());
        $this->assertContains('id="create_duration"', $crawler->html());
        $this->assertContains('name="create[duration]"', $crawler->html());
        $this->assertContains('id="create_parent"', $crawler->html());
        $this->assertContains('name="create[workPackageCategory]"', $crawler->html());
        $this->assertContains('id="create_workPackageCategory"', $crawler->html());
        $this->assertContains('name="create[parent]"', $crawler->html());
        $this->assertContains('id="create_colorStatus"', $crawler->html());
        $this->assertContains('name="create[colorStatus]"', $crawler->html());
        $this->assertContains('id="create_responsibility"', $crawler->html());
        $this->assertContains('name="create[responsibility]"', $crawler->html());
        $this->assertContains('id="create_project"', $crawler->html());
        $this->assertContains('name="create[project]"', $crawler->html());
        $this->assertContains('id="create_calendar"', $crawler->html());
        $this->assertContains('name="create[calendar]"', $crawler->html());
        $this->assertContains('id="create_scheduledStartAt"', $crawler->html());
        $this->assertContains('name="create[scheduledStartAt]"', $crawler->html());
        $this->assertContains('id="create_scheduledFinishAt"', $crawler->html());
        $this->assertContains('name="create[scheduledFinishAt]"', $crawler->html());
        $this->assertContains('id="create_forecastStartAt"', $crawler->html());
        $this->assertContains('name="create[forecastStartAt]"', $crawler->html());
        $this->assertContains('id="create_forecastFinishAt"', $crawler->html());
        $this->assertContains('name="create[forecastFinishAt]"', $crawler->html());
        $this->assertContains('id="create_actualStartAt"', $crawler->html());
        $this->assertContains('name="create[actualStartAt]"', $crawler->html());
        $this->assertContains('id="create_actualFinishAt"', $crawler->html());
        $this->assertContains('name="create[actualFinishAt]"', $crawler->html());
        $this->assertContains('id="create_content"', $crawler->html());
        $this->assertContains('name="create[content]"', $crawler->html());
        $this->assertContains('id="create_results"', $crawler->html());
        $this->assertContains('name="create[results]"', $crawler->html());
        $this->assertContains('id="create_isKeyMilestone"', $crawler->html());
        $this->assertContains('name="create[isKeyMilestone]"', $crawler->html());
        $this->assertContains('id="create_labels"', $crawler->html());
        $this->assertContains('name="create[labels][]"', $crawler->html());
        $this->assertContains('id="create_dependencies"', $crawler->html());
        $this->assertContains('name="create[dependencies][]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/workpackage/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['create[name]'] = '';
        $form['create[progress]'] = 0;
        $form['create[duration]'] = 0;
        $crawler = $this->client->submit($form);

        $this->assertContains('The name field should not be blank', $crawler->html());
        $this->assertContains('The type field should not be blank', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testProgressIsValidOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/workpackage/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['create[name]'] = 'workpackage';
        $form['create[type]'] = WorkPackage::TYPE_TASK;
        $form['create[progress]'] = '-2';
        $form['create[duration]'] = '0';

        $crawler = $this->client->submit($form);

        $this->assertContains('The progress field should contain numbers greater than or equal to 0', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/workpackage/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['create[name]'] = 'workpackage10';
        $form['create[type]'] = WorkPackage::TYPE_TASK;
        $form['create[duration]'] = '0';
        $form['create[scheduledStartAt]'] = '01-01-2018';
        $form['create[scheduledFinishAt]'] = '02-01-2018';
        $form['create[forecastStartAt]'] = '01-01-2018';
        $form['create[forecastFinishAt]'] = '02-01-2018';
        $form['create[responsibility]'] = 3;

        $this->client->submit($form);
        $response = $this->client->getResponse();

        $this->assertTrue($response->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('WorkPackage successfully created!', $this->client->getResponse()->getContent());
    }

    public function testDeleteAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $workPackage = (new WorkPackage())
            ->setType(WorkPackage::TYPE_TASK)
            ->setPuid('11')
            ->setName('workpackage11')
            ->setDuration(0)
        ;
        $this->em->persist($workPackage);
        $this->em->flush();

        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/admin/workpackage/%d/edit', $workPackage->getId()));

        $link = $crawler->selectLink('Delete')->link();
        $this->client->click($link);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('WorkPackage successfully deleted!', $this->client->getResponse()->getContent());
    }

    public function testFormIsDisplayedOnEditPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/workpackage/1/edit');

        $this->assertContains('id="create_name"', $crawler->html());
        $this->assertContains('name="create[name]"', $crawler->html());
        $this->assertContains('id="create_type"', $crawler->html());
        $this->assertContains('name="create[type]"', $crawler->html());
        $this->assertContains('id="create_workPackageStatus"', $crawler->html());
        $this->assertContains('name="create[workPackageStatus]"', $crawler->html());
        $this->assertContains('id="create_progress"', $crawler->html());
        $this->assertContains('name="create[progress]"', $crawler->html());
        $this->assertContains('id="create_duration"', $crawler->html());
        $this->assertContains('name="create[duration]"', $crawler->html());
        $this->assertContains('id="create_parent"', $crawler->html());
        $this->assertContains('name="create[parent]"', $crawler->html());
        $this->assertContains('name="create[workPackageCategory]"', $crawler->html());
        $this->assertContains('id="create_workPackageCategory"', $crawler->html());
        $this->assertContains('id="create_colorStatus"', $crawler->html());
        $this->assertContains('name="create[colorStatus]"', $crawler->html());
        $this->assertContains('id="create_responsibility"', $crawler->html());
        $this->assertContains('name="create[responsibility]"', $crawler->html());
        $this->assertContains('id="create_project"', $crawler->html());
        $this->assertContains('name="create[project]"', $crawler->html());
        $this->assertContains('id="create_calendar"', $crawler->html());
        $this->assertContains('name="create[calendar]"', $crawler->html());
        $this->assertContains('id="create_scheduledStartAt"', $crawler->html());
        $this->assertContains('name="create[scheduledStartAt]"', $crawler->html());
        $this->assertContains('id="create_scheduledFinishAt"', $crawler->html());
        $this->assertContains('name="create[scheduledFinishAt]"', $crawler->html());
        $this->assertContains('id="create_forecastStartAt"', $crawler->html());
        $this->assertContains('name="create[forecastStartAt]"', $crawler->html());
        $this->assertContains('id="create_forecastFinishAt"', $crawler->html());
        $this->assertContains('name="create[forecastFinishAt]"', $crawler->html());
        $this->assertContains('id="create_actualStartAt"', $crawler->html());
        $this->assertContains('name="create[actualStartAt]"', $crawler->html());
        $this->assertContains('id="create_actualFinishAt"', $crawler->html());
        $this->assertContains('name="create[actualFinishAt]"', $crawler->html());
        $this->assertContains('id="create_content"', $crawler->html());
        $this->assertContains('name="create[content]"', $crawler->html());
        $this->assertContains('id="create_results"', $crawler->html());
        $this->assertContains('name="create[results]"', $crawler->html());
        $this->assertContains('id="create_isKeyMilestone"', $crawler->html());
        $this->assertContains('name="create[isKeyMilestone]"', $crawler->html());
        $this->assertContains('id="create_labels"', $crawler->html());
        $this->assertContains('name="create[labels][]"', $crawler->html());
        $this->assertContains('id="create_dependencies"', $crawler->html());
        $this->assertContains('name="create[dependencies][]"', $crawler->html());
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
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/workpackage/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[name]'] = '';
        $form['create[progress]'] = 0;
        $form['create[duration]'] = 0;

        $crawler = $this->client->submit($form);

        $this->assertContains('The name field should not be blank', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testProgressIsValidOnEditPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/workpackage/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[progress]'] = '-2';

        $crawler = $this->client->submit($form);

        $this->assertContains('The progress field should contain numbers greater than or equal to 0', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEditAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/workpackage/2/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[name]'] = 'workpackage2';
        $form['create[workPackageCategory]'] = 1;
        $form['create[scheduledStartAt]'] = '01-01-2018';
        $form['create[scheduledFinishAt]'] = '02-01-2018';
        $form['create[forecastStartAt]'] = '01-01-2018';
        $form['create[forecastFinishAt]'] = '02-01-2018';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('WorkPackage successfully edited!', $this->client->getResponse()->getContent());
    }

    public function testDataTableOnListPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/workpackage/list');

        $this->assertEquals(1, $crawler->filter('#data-table-command')->count());
        $this->assertContains('data-column-id="id"', $crawler->html());
        $this->assertContains('data-column-id="puid"', $crawler->html());
        $this->assertContains('data-column-id="name"', $crawler->html());
        $this->assertContains('data-column-id="workPackageCategoryName"', $crawler->html());
        $this->assertContains('data-column-id="responsibilityFullName"', $crawler->html());
        $this->assertContains('data-column-id="scheduledStartAt"', $crawler->html());
        $this->assertContains('data-column-id="scheduledFinishAt"', $crawler->html());
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
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/workpackage/1/show');

        $this->assertEquals(1, $crawler->filter('.dropdown-menu-right')->count());
        $this->assertEquals(1, $crawler->filter('.table-responsive')->count());
        $this->assertEquals(1, $crawler->filter('.table-striped')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
