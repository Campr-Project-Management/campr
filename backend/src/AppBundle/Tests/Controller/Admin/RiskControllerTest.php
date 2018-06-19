<?php

namespace AppBundle\Tests\Controller\Admin;

use AppBundle\Entity\Risk;
use AppBundle\Entity\RiskStatus;
use AppBundle\Entity\Status;
use MainBundle\Tests\Controller\BaseController;
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

        $this->assertContains('id="admin_title"', $crawler->html());
        $this->assertContains('name="admin[title]"', $crawler->html());
        $this->assertContains('id="admin_project"', $crawler->html());
        $this->assertContains('name="admin[project]"', $crawler->html());
        $this->assertContains('id="admin_description"', $crawler->html());
        $this->assertContains('name="admin[description]"', $crawler->html());
        $this->assertContains('id="admin_impact"', $crawler->html());
        $this->assertContains('name="admin[impact]"', $crawler->html());
        $this->assertContains('id="admin_cost"', $crawler->html());
        $this->assertContains('name="admin[cost]"', $crawler->html());
        $this->assertContains('id="admin_delay"', $crawler->html());
        $this->assertContains('name="admin[delay]"', $crawler->html());
        $this->assertContains('id="admin_priority"', $crawler->html());
        $this->assertContains('name="admin[priority]"', $crawler->html());
        $this->assertContains('id="admin_riskStrategy"', $crawler->html());
        $this->assertContains('name="admin[riskStrategy]"', $crawler->html());
        $this->assertContains('id="admin_riskCategory"', $crawler->html());
        $this->assertContains('name="admin[riskCategory]"', $crawler->html());
        $this->assertContains('id="admin_responsibility"', $crawler->html());
        $this->assertContains('name="admin[responsibility]"', $crawler->html());
        $this->assertContains('id="admin_dueDate"', $crawler->html());
        $this->assertContains('name="admin[dueDate]"', $crawler->html());
        $this->assertContains('id="admin_riskStatus"', $crawler->html());
        $this->assertContains('name="admin[riskStatus]"', $crawler->html());
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

        $this->assertContains('The title field should not be blank', $crawler->html());
        $this->assertContains('The description field should not be blank', $crawler->html());
        $this->assertContains('The cost field should not be blank', $crawler->html());
        $this->assertContains('The delay field should not be blank', $crawler->html());
        $this->assertContains('The priority field should not be blank', $crawler->html());
    }

    public function testCreateAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $status = (new RiskStatus())
            ->setName('status-test')
        ;
        $this->em->persist($status);
        $this->em->flush();

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/risk/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['admin[title]'] = 'risk3';
        $form['admin[impact]'] = 10;
        $form['admin[probability]'] = 100;
        $form['admin[description]'] = 'risk-description';
        $form['admin[cost]'] = 1;
        $form['admin[delay]'] = 1;
        $form['admin[delayUnit]'] = 'choices.days';
        $form['admin[priority]'] = 1;
        $form['admin[riskStatus]'] = $status->getId();

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
            ->getRepository(RiskStatus::class)
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

        $status = (new RiskStatus())
            ->setName('status-test')
        ;
        $this->em->persist($status);

        $risk = new Risk();
        $risk->setTitle('risk-title3');
        $risk->setImpact(1);
        $risk->setProbability(1);
        $risk->setDescription('risk-description');
        $risk->setCost(1);
        $risk->setDelay(1);
        $risk->setDelayUnit('days');
        $risk->setRiskStatus($status);

        $risk->setPriority(1);

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
            ->getRepository(RiskStatus::class)
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

        $this->assertContains('id="admin_title"', $crawler->html());
        $this->assertContains('name="admin[title]"', $crawler->html());
        $this->assertContains('id="admin_project"', $crawler->html());
        $this->assertContains('name="admin[project]"', $crawler->html());
        $this->assertContains('id="admin_description"', $crawler->html());
        $this->assertContains('name="admin[description]"', $crawler->html());
        $this->assertContains('id="admin_impact"', $crawler->html());
        $this->assertContains('name="admin[impact]"', $crawler->html());
        $this->assertContains('id="admin_cost"', $crawler->html());
        $this->assertContains('name="admin[cost]"', $crawler->html());
        $this->assertContains('id="admin_delay"', $crawler->html());
        $this->assertContains('name="admin[delay]"', $crawler->html());
        $this->assertContains('id="admin_priority"', $crawler->html());
        $this->assertContains('name="admin[priority]"', $crawler->html());
        $this->assertContains('id="admin_riskStrategy"', $crawler->html());
        $this->assertContains('name="admin[riskStrategy]"', $crawler->html());
        $this->assertContains('id="admin_riskCategory"', $crawler->html());
        $this->assertContains('name="admin[riskCategory]"', $crawler->html());
        $this->assertContains('id="admin_responsibility"', $crawler->html());
        $this->assertContains('name="admin[responsibility]"', $crawler->html());
        $this->assertContains('id="admin_dueDate"', $crawler->html());
        $this->assertContains('name="admin[dueDate]"', $crawler->html());
        $this->assertContains('id="admin_riskStatus"', $crawler->html());
        $this->assertContains('name="admin[riskStatus]"', $crawler->html());
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
        $form['admin[title]'] = '';
        $form['admin[description]'] = '';
        $form['admin[cost]'] = '';
        $form['admin[delay]'] = '';
        $form['admin[priority]'] = '';

        $crawler = $this->client->submit($form);

        $this->assertContains('The title field should not be blank', $crawler->html());
        $this->assertContains('The description field should not be blank', $crawler->html());
        $this->assertContains('The cost field should not be blank', $crawler->html());
        $this->assertContains('The delay field should not be blank', $crawler->html());
        $this->assertContains('The priority field should not be blank', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEditAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/risk/2/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['admin[title]'] = 'title2';
        $form['admin[delayUnit]'] = 'choices.days';

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
        $this->assertContains('data-column-id="riskStrategyName"', $crawler->html());
        $this->assertContains('data-column-id="riskCategoryName"', $crawler->html());
        $this->assertContains('data-column-id="responsibilityFullName"', $crawler->html());
        $this->assertContains('data-column-id="dueDate"', $crawler->html());
        $this->assertContains('data-column-id="statusName"', $crawler->html());
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
