<?php

namespace AppBundle\Tests\Controller\Admin;

use AppBundle\Entity\Timephase;
use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TimephaseControllerTest extends BaseController
{
    public function testFormIsDisplayedOnCreatePage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/timephase/create');

        $this->assertContains('id="create_type"', $crawler->html());
        $this->assertContains('name="create[type]"', $crawler->html());
        $this->assertContains('id="create_assignment"', $crawler->html());
        $this->assertContains('name="create[assignment]"', $crawler->html());
        $this->assertContains('id="create_unit"', $crawler->html());
        $this->assertContains('name="create[unit]"', $crawler->html());
        $this->assertContains('id="create_value"', $crawler->html());
        $this->assertContains('name="create[value]"', $crawler->html());
        $this->assertContains('id="create_startedAt"', $crawler->html());
        $this->assertContains('name="create[startedAt]"', $crawler->html());
        $this->assertContains('id="create_finishedAt"', $crawler->html());
        $this->assertContains('name="create[finishedAt]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnCreatePage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/timephase/create');

        $form = $crawler->filter('#create-form')->first()->form();

        $crawler = $this->client->submit($form);

        $this->assertContains('The type field should not be blank', $crawler->html());
        $this->assertContains('The unit field should not be blank', $crawler->html());
        $this->assertContains('The value field should not be blank', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testTypeIsNumericOnCreatePage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/timephase/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['create[type]'] = '-1';
        $form['create[unit]'] = '1';
        $form['create[value]'] = 'value3';
        $crawler = $this->client->submit($form);

        $this->assertContains('The type field should contain numbers greater than or equal to 0', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testUnitIsNumericOnCreatePage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/timephase/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['create[type]'] = '1';
        $form['create[unit]'] = '-1';
        $form['create[value]'] = 'value3';
        $crawler = $this->client->submit($form);

        $this->assertContains('The unit field should contain numbers greater than or equal to 0', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateAction()
    {
        $this->login();

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/timephase/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['create[type]'] = '3';
        $form['create[unit]'] = '3';
        $form['create[value]'] = 'value3';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Timephase successfully created!', $this->client->getResponse()->getContent());

        $timephase = $this
            ->em
            ->getRepository(Timephase::class)
            ->findOneBy(
                [
                    'value' => 'value3',
                ]
            );
        $this->em->remove($timephase);
        $this->em->flush();
    }

    public function testDeleteAction()
    {
        $this->markTestSkipped();
        $this->login();

        $timephase = (new Timephase())
            ->setType(4)
            ->setUnit(4)
            ->setValue('value4');
        $this->em->persist($timephase);
        $this->em->flush();

        $this->client->request(
            Request::METHOD_GET,
            sprintf('/admin/timephase/%d/delete', $timephase->getId())
        );
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Timephase successfully deleted!', $this->client->getResponse()->getContent());
    }

    public function testFormIsDisplayedOnEditPage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/timephase/1/edit');

        $this->assertContains('id="create_type"', $crawler->html());
        $this->assertContains('name="create[type]"', $crawler->html());
        $this->assertContains('id="create_assignment"', $crawler->html());
        $this->assertContains('name="create[assignment]"', $crawler->html());
        $this->assertContains('id="create_unit"', $crawler->html());
        $this->assertContains('name="create[unit]"', $crawler->html());
        $this->assertContains('id="create_value"', $crawler->html());
        $this->assertContains('name="create[value]"', $crawler->html());
        $this->assertContains('id="create_startedAt"', $crawler->html());
        $this->assertContains('name="create[startedAt]"', $crawler->html());
        $this->assertContains('id="create_finishedAt"', $crawler->html());
        $this->assertContains('name="create[finishedAt]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());
        $this->assertContains('class="zmdi zmdi-delete"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnEditPage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/timephase/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[type]'] = '';
        $form['create[unit]'] = '';
        $form['create[value]'] = '';
        $crawler = $this->client->submit($form);

        $this->assertContains('The type field should not be blank', $crawler->html());
        $this->assertContains('The unit field should not be blank', $crawler->html());
        $this->assertContains('The value field should not be blank', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testTypeIsNumericOnEditPage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/timephase/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[type]'] = '-1';
        $crawler = $this->client->submit($form);

        $this->assertContains('The type field should contain numbers greater than or equal to 0', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testUnitIsNumericOnEditPage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/timephase/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[unit]'] = '-1';
        $crawler = $this->client->submit($form);

        $this->assertContains('The unit field should contain numbers greater than or equal to 0', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEditAction()
    {
        $this->login();

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/timephase/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[type]'] = '1';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Timephase successfully edited!', $this->client->getResponse()->getContent());
    }

    public function testDataTableOnListPage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/timephase/list');

        $this->assertEquals(1, $crawler->filter('#data-table-command')->count());
        $this->assertContains('data-column-id="id"', $crawler->html());
        $this->assertContains('data-column-id="type"', $crawler->html());
        $this->assertContains('data-column-id="assignmentName"', $crawler->html());
        $this->assertContains('data-column-id="unit"', $crawler->html());
        $this->assertContains('data-column-id="value"', $crawler->html());
        $this->assertContains('data-column-id="commands"', $crawler->html());
        $this->assertEquals(1, $crawler->filter('.zmdi-plus')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testTableStructureOnShowAction()
    {
        $this->login();

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/todo/1/show');

        $this->assertEquals(1, $crawler->filter('.dropdown-menu-right')->count());
        $this->assertEquals(1, $crawler->filter('.table-responsive')->count());
        $this->assertEquals(1, $crawler->filter('.table-striped')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
