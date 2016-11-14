<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\Risk;
use AppBundle\Entity\Status;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RiskControllerTest extends BaseController
{
    public function testFormIsDisplayedOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/risk/create');

        $this->assertContains('id="create_title"', $crawler->html());
        $this->assertContains('name="create[title]"', $crawler->html());
        $this->assertContains('id="create_description"', $crawler->html());
        $this->assertContains('name="create[description]"', $crawler->html());
        $this->assertContains('id="create_impact"', $crawler->html());
        $this->assertContains('name="create[impact]"', $crawler->html());
        $this->assertContains('id="create_cost"', $crawler->html());
        $this->assertContains('name="create[cost]"', $crawler->html());
        $this->assertContains('id="create_budget"', $crawler->html());
        $this->assertContains('name="create[budget]"', $crawler->html());
        $this->assertContains('id="create_delay"', $crawler->html());
        $this->assertContains('name="create[delay]"', $crawler->html());
        $this->assertContains('id="create_priority"', $crawler->html());
        $this->assertContains('name="create[priority]"', $crawler->html());
        $this->assertContains('id="create_riskStrategy"', $crawler->html());
        $this->assertContains('name="create[riskStrategy]"', $crawler->html());
        $this->assertContains('id="create_riskCategory"', $crawler->html());
        $this->assertContains('name="create[riskCategory]"', $crawler->html());
        $this->assertContains('id="create_measure"', $crawler->html());
        $this->assertContains('name="create[measure]"', $crawler->html());
        $this->assertContains('id="create_responsibility"', $crawler->html());
        $this->assertContains('name="create[responsibility]"', $crawler->html());
        $this->assertContains('id="create_dueDate"', $crawler->html());
        $this->assertContains('name="create[dueDate]"', $crawler->html());
        $this->assertContains('id="create_status"', $crawler->html());
        $this->assertContains('name="create[status]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/risk/create');

        $form = $crawler->filter('#create-form')->first()->form();

        $crawler = $this->client->submit($form);

        $this->assertContains('The title should not be blank', $crawler->html());
        $this->assertContains('The description should not be blank', $crawler->html());
        $this->assertContains('The cost should not be blank', $crawler->html());
        $this->assertContains('The budget should not be blank', $crawler->html());
        $this->assertContains('The delay should not be blank', $crawler->html());
        $this->assertContains('The priority should not be blank', $crawler->html());
        $this->assertContains('The measure should not be blank', $crawler->html());
    }

    public function testCreateAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $status = (new Status())
            ->setName('status-test')
        ;
        $this->em->persist($status);
        $this->em->flush();

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/risk/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['create[title]'] = 'risk3';
        $form['create[description]'] = 'risk-description';
        $form['create[cost]'] = 'risk-cost';
        $form['create[budget]'] = 'risk-budget';
        $form['create[delay]'] = 'risk-delay';
        $form['create[priority]'] = 'risk-priority';
        $form['create[measure]'] = 'risk-measure';
        $form['create[status]'] = $status->getId();

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Risk successfully created!', $this->client->getResponse()->getContent());

        $risk = $this
            ->em
            ->getRepository(Risk::class)
            ->findOneBy([
                'title' => 'risk3',
            ])
        ;
        $status = $this
            ->em
            ->getRepository(Status::class)
            ->findOneBy([
                'name' => 'status-test',
            ])
        ;
        $this->em->remove($risk);
        $this->em->remove($status);
        $this->em->flush();
    }

    public function testDeleteAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $status = (new Status())
            ->setName('status-test')
        ;
        $this->em->persist($status);

        $risk = (new Risk())
            ->setTitle('risk-title3')
            ->setDescription('risk-description')
            ->setCost('risk-cost')
            ->setBudget('risk-budget')
            ->setDelay('risk-delay')
            ->setPriority('risk-priority')
            ->setMeasure('risk-measure')
            ->setStatus($status)
        ;

        $this->em->persist($risk);
        $this->em->flush();

        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/admin/risk/%d/edit', $risk->getId()));

        $link = $crawler->selectLink('Delete')->link();
        $this->client->click($link);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Risk successfully deleted!', $this->client->getResponse()->getContent());

        $status = $this
            ->em
            ->getRepository(Status::class)
            ->findOneBy([
                'name' => 'status-test',
            ])
        ;
        $this->em->remove($status);
        $this->em->flush();
    }

    public function testFormIsDisplayedOnEditPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/risk/1/edit');

        $this->assertContains('id="create_title"', $crawler->html());
        $this->assertContains('name="create[title]"', $crawler->html());
        $this->assertContains('id="create_description"', $crawler->html());
        $this->assertContains('name="create[description]"', $crawler->html());
        $this->assertContains('id="create_impact"', $crawler->html());
        $this->assertContains('name="create[impact]"', $crawler->html());
        $this->assertContains('id="create_cost"', $crawler->html());
        $this->assertContains('name="create[cost]"', $crawler->html());
        $this->assertContains('id="create_budget"', $crawler->html());
        $this->assertContains('name="create[budget]"', $crawler->html());
        $this->assertContains('id="create_delay"', $crawler->html());
        $this->assertContains('name="create[delay]"', $crawler->html());
        $this->assertContains('id="create_priority"', $crawler->html());
        $this->assertContains('name="create[priority]"', $crawler->html());
        $this->assertContains('id="create_riskStrategy"', $crawler->html());
        $this->assertContains('name="create[riskStrategy]"', $crawler->html());
        $this->assertContains('id="create_riskCategory"', $crawler->html());
        $this->assertContains('name="create[riskCategory]"', $crawler->html());
        $this->assertContains('id="create_measure"', $crawler->html());
        $this->assertContains('name="create[measure]"', $crawler->html());
        $this->assertContains('id="create_responsibility"', $crawler->html());
        $this->assertContains('name="create[responsibility]"', $crawler->html());
        $this->assertContains('id="create_dueDate"', $crawler->html());
        $this->assertContains('name="create[dueDate]"', $crawler->html());
        $this->assertContains('id="create_status"', $crawler->html());
        $this->assertContains('name="create[status]"', $crawler->html());
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
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/risk/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[title]'] = '';
        $form['create[description]'] = '';
        $form['create[cost]'] = '';
        $form['create[budget]'] = '';
        $form['create[delay]'] = '';
        $form['create[priority]'] = '';
        $form['create[measure]'] = '';

        $crawler = $this->client->submit($form);

        $this->assertContains('The title should not be blank', $crawler->html());
        $this->assertContains('The description should not be blank', $crawler->html());
        $this->assertContains('The cost should not be blank', $crawler->html());
        $this->assertContains('The budget should not be blank', $crawler->html());
        $this->assertContains('The delay should not be blank', $crawler->html());
        $this->assertContains('The priority should not be blank', $crawler->html());
        $this->assertContains('The measure should not be blank', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEditAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/risk/2/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[title]'] = 'title2';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Risk successfully edited!', $this->client->getResponse()->getContent());
    }

    public function testDataTableOnListPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/risk/list');

        $this->assertEquals(1, $crawler->filter('#data-table-command')->count());
        $this->assertContains('data-column-id="id"', $crawler->html());
        $this->assertContains('data-column-id="title"', $crawler->html());
        $this->assertContains('data-column-id="riskStrategy"', $crawler->html());
        $this->assertContains('data-column-id="riskCategory"', $crawler->html());
        $this->assertContains('data-column-id="responsibility"', $crawler->html());
        $this->assertContains('data-column-id="dueDate"', $crawler->html());
        $this->assertContains('data-column-id="status"', $crawler->html());
        $this->assertContains('data-column-id="commands"', $crawler->html());
        $this->assertEquals(1, $crawler->filter('.zmdi-plus')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testTableStructureOnShowAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/risk/1/show');

        $this->assertEquals(1, $crawler->filter('.dropdown-menu-right')->count());
        $this->assertEquals(1, $crawler->filter('.table-responsive')->count());
        $this->assertEquals(1, $crawler->filter('.table-striped')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
