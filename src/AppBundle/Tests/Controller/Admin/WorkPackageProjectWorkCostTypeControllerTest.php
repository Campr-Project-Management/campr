<?php

namespace AppBundle\Tests\Controller\Admin;

use AppBundle\Entity\WorkPackageProjectWorkCostType;
use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WorkPackageProjectWorkCostTypeControllerTest extends BaseController
{
    public function testFormIsDisplayedOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/workpackage-projectworkcost/create');

        $this->assertContains('id="create_name"', $crawler->html());
        $this->assertContains('name="create[name]"', $crawler->html());
        $this->assertContains('id="create_workPackage"', $crawler->html());
        $this->assertContains('name="create[workPackage]"', $crawler->html());
        $this->assertContains('id="create_projectWorkCostType"', $crawler->html());
        $this->assertContains('name="create[projectWorkCostType]"', $crawler->html());
        $this->assertContains('id="create_calendar"', $crawler->html());
        $this->assertContains('name="create[calendar]"', $crawler->html());
        $this->assertContains('id="create_base"', $crawler->html());
        $this->assertContains('name="create[base]"', $crawler->html());
        $this->assertContains('id="create_change"', $crawler->html());
        $this->assertContains('name="create[change]"', $crawler->html());
        $this->assertContains('id="create_actual"', $crawler->html());
        $this->assertContains('name="create[actual]"', $crawler->html());
        $this->assertContains('id="create_remaining"', $crawler->html());
        $this->assertContains('name="create[remaining]"', $crawler->html());
        $this->assertContains('id="create_forecast"', $crawler->html());
        $this->assertContains('name="create[forecast]"', $crawler->html());
        $this->assertContains('id="create_isGeneric"', $crawler->html());
        $this->assertContains('name="create[isGeneric]"', $crawler->html());
        $this->assertContains('id="create_isInactive"', $crawler->html());
        $this->assertContains('name="create[isInactive]"', $crawler->html());
        $this->assertContains('id="create_isEnterprise"', $crawler->html());
        $this->assertContains('name="create[isEnterprise]"', $crawler->html());
        $this->assertContains('id="create_isCostResource"', $crawler->html());
        $this->assertContains('name="create[isCostResource]"', $crawler->html());
        $this->assertContains('id="create_isBudget"', $crawler->html());
        $this->assertContains('name="create[isBudget]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/workpackage-projectworkcost/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $crawler = $this->client->submit($form);

        $this->assertContains('The name field should not be blank', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/workpackage-projectworkcost/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['create[name]'] = 'work-package-project-work-cost-type3';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Work Package Project Work Cost Type successfully created!', $this->client->getResponse()->getContent());

        $workPackageProjectWorkCostType = $this
            ->em
            ->getRepository(WorkPackageProjectWorkCostType::class)
            ->findOneBy([
                'name' => 'work-package-project-work-cost-type3',
            ])
        ;
        $this->em->remove($workPackageProjectWorkCostType);
        $this->em->flush();
    }

    public function testDeleteAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $workPackageProjectWorkCostType = (new WorkPackageProjectWorkCostType())
            ->setName('work-package-project-work-cost-type4')
        ;
        $this->em->persist($workPackageProjectWorkCostType);
        $this->em->flush();

        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/admin/workpackage-projectworkcost/%d/edit', $workPackageProjectWorkCostType->getId()));

        $link = $crawler->selectLink('Delete')->link();
        $this->client->click($link);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Work Package Project Work Cost Type successfully deleted!', $this->client->getResponse()->getContent());
    }

    public function testFormIsDisplayedOnEditPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/workpackage-projectworkcost/1/edit');

        $this->assertContains('id="create_name"', $crawler->html());
        $this->assertContains('name="create[name]"', $crawler->html());
        $this->assertContains('id="create_workPackage"', $crawler->html());
        $this->assertContains('name="create[workPackage]"', $crawler->html());
        $this->assertContains('id="create_projectWorkCostType"', $crawler->html());
        $this->assertContains('name="create[projectWorkCostType]"', $crawler->html());
        $this->assertContains('id="create_calendar"', $crawler->html());
        $this->assertContains('name="create[calendar]"', $crawler->html());
        $this->assertContains('id="create_base"', $crawler->html());
        $this->assertContains('name="create[base]"', $crawler->html());
        $this->assertContains('id="create_change"', $crawler->html());
        $this->assertContains('name="create[change]"', $crawler->html());
        $this->assertContains('id="create_actual"', $crawler->html());
        $this->assertContains('name="create[actual]"', $crawler->html());
        $this->assertContains('id="create_remaining"', $crawler->html());
        $this->assertContains('name="create[remaining]"', $crawler->html());
        $this->assertContains('id="create_forecast"', $crawler->html());
        $this->assertContains('name="create[forecast]"', $crawler->html());
        $this->assertContains('id="create_isGeneric"', $crawler->html());
        $this->assertContains('name="create[isGeneric]"', $crawler->html());
        $this->assertContains('id="create_isInactive"', $crawler->html());
        $this->assertContains('name="create[isInactive]"', $crawler->html());
        $this->assertContains('id="create_isEnterprise"', $crawler->html());
        $this->assertContains('name="create[isEnterprise]"', $crawler->html());
        $this->assertContains('id="create_isCostResource"', $crawler->html());
        $this->assertContains('name="create[isCostResource]"', $crawler->html());
        $this->assertContains('id="create_isBudget"', $crawler->html());
        $this->assertContains('name="create[isBudget]"', $crawler->html());
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
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/workpackage-projectworkcost/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[name]'] = '';

        $crawler = $this->client->submit($form);

        $this->assertContains('The name field should not be blank', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEditAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/workpackage-projectworkcost/2/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[name]'] = 'work-package-project-work-cost-type2';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Work Package Project Work Cost Type successfully edited!', $this->client->getResponse()->getContent());
    }

    public function testDataTableOnListPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/workpackage-projectworkcost/list');

        $this->assertEquals(1, $crawler->filter('#data-table-command')->count());
        $this->assertContains('data-column-id="id"', $crawler->html());
        $this->assertContains('data-column-id="name"', $crawler->html());
        $this->assertContains('data-column-id="workPackageName"', $crawler->html());
        $this->assertContains('data-column-id="projectWorkCostTypeName"', $crawler->html());
        $this->assertContains('data-column-id="createdAt"', $crawler->html());
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
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/workpackage-projectworkcost/1/show');

        $this->assertEquals(1, $crawler->filter('.dropdown-menu-right')->count());
        $this->assertEquals(1, $crawler->filter('.table-responsive')->count());
        $this->assertEquals(1, $crawler->filter('.table-striped')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
