<?php

namespace AppBundle\Tests\Controller\Admin;

use AppBundle\Entity\Label;
use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LabelControllerTest extends BaseController
{
    public function testFormIsDisplayedOnCreatePage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/label/create');

        $this->assertContains('id="label_title"', $crawler->html());
        $this->assertContains('name="label[title]"', $crawler->html());
        $this->assertContains('id="label_description"', $crawler->html());
        $this->assertContains('name="label[description]"', $crawler->html());
        $this->assertContains('id="label_color"', $crawler->html());
        $this->assertContains('name="label[color]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnCreatePage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/label/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['label[color]'] = '';

        $crawler = $this->client->submit($form);

        $this->assertContains('The title field should not be blank', $crawler->html());
        $this->assertContains('The color field should not be blank', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testColorIsValidOnCreatePage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/label/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['label[title]'] = 'label';
        $form['label[color]'] = '12345678';

        $crawler = $this->client->submit($form);

        $this->assertContains('The color field should have maximum 7 characters', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateAction()
    {
        $this->login();

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/label/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['label[title]'] = 'label-priority';
        $form['label[color]'] = '123456';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Label successfully created!', $this->client->getResponse()->getContent());

        $label = $this
            ->em
            ->getRepository(Label::class)
            ->findOneByTitle('label-priority')
        ;
        $this->em->remove($label);
        $this->em->flush();
    }

    public function testDeleteAction()
    {
        $this->login();

        $label = new Label();
        $label->setTitle('proj-label');
        $label->setColor('1');
        $this->em->persist($label);
        $this->em->flush();

        $this->client->request(Request::METHOD_GET, sprintf('/admin/label/%d/delete', $label->getId()));
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Label successfully deleted!', $this->client->getResponse()->getContent());
    }

    public function testFormIsDisplayedOnEditPage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/label/1/edit');

        $this->assertContains('id="label_title"', $crawler->html());
        $this->assertContains('name="label[title]"', $crawler->html());
        $this->assertContains('id="label_description"', $crawler->html());
        $this->assertContains('name="label[description]"', $crawler->html());
        $this->assertContains('id="label_color"', $crawler->html());
        $this->assertContains('name="label[color]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());
        $this->assertContains('class="zmdi zmdi-delete"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnEditPage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/label/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['label[color]'] = '';

        $crawler = $this->client->submit($form);

        $this->assertContains('The color field should not be blank', $crawler->html());
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEditAction()
    {
        $this->login();

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/label/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['label[description]'] = 'new-descript';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Label successfully edited!', $this->client->getResponse()->getContent());
    }

    public function testDataTableOnListPage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/label/list');

        $this->assertEquals(1, $crawler->filter('#data-table-command')->count());
        $this->assertContains('data-column-id="id"', $crawler->html());
        $this->assertContains('data-column-id="title"', $crawler->html());
        $this->assertContains('data-column-id="color"', $crawler->html());
        $this->assertContains('data-column-id="description"', $crawler->html());
        $this->assertEquals(1, $crawler->filter('.zmdi-plus')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testTableStructureOnShowAction()
    {
        $this->login();

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/label/1/show');

        $this->assertEquals(1, $crawler->filter('.dropdown-menu-right')->count());
        $this->assertEquals(1, $crawler->filter('.table-responsive')->count());
        $this->assertEquals(1, $crawler->filter('.table-striped')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
