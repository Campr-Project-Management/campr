<?php

namespace AppBundle\Tests\Controller\Admin;

use AppBundle\Entity\ProjectComplexity;
use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProjectComplexityControllerTest extends BaseController
{
    public function testFormIsDisplayedOnCreatePage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-complexity/create');

        $this->assertContains('id="create_name"', $crawler->html());
        $this->assertContains('name="create[name]"', $crawler->html());
        $this->assertContains('id="create_sequence"', $crawler->html());
        $this->assertContains('name="create[sequence]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnCreatePage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-complexity/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['create[sequence]'] = '';

        $crawler = $this->client->submit($form);

        $this->assertContains('The name field should not be blank', $crawler->html());
        $this->assertContains('The sequence field should not be blank', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testNameIsUniqueOnCreatePage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-complexity/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['create[name]'] = 'project-complexity1';

        $crawler = $this->client->submit($form);

        $this->assertContains('That name is taken', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testSequenceIsValidOnCreatePage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-complexity/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['create[name]'] = 'project-complexity';
        $form['create[sequence]'] = 'sequence';

        $crawler = $this->client->submit($form);

        $this->assertContains('The sequence field should contain numbers greater than or equal to 0', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateAction()
    {
        $this->login();

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-complexity/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['create[name]'] = 'project-complexity3';
        $form['create[sequence]'] = '3';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Project complexity successfully created!', $this->client->getResponse()->getContent());

        $projectComplexity = $this
            ->em
            ->getRepository(ProjectComplexity::class)
            ->findOneBy(
                [
                    'name' => 'project-complexity3',
                ]
            );
        $this->em->remove($projectComplexity);
        $this->em->flush();
    }

    public function testDeleteAction()
    {
        $this->login();

        $projectComplexity = (new ProjectComplexity())
            ->setName('project-complexity4')
            ->setSequence('1');
        $this->em->persist($projectComplexity);
        $this->em->flush();

        $this->client->request(
            Request::METHOD_GET,
            sprintf('/admin/project-complexity/%d/delete', $projectComplexity->getId())
        );
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Project complexity successfully deleted!', $this->client->getResponse()->getContent());
    }

    public function testFormIsDisplayedOnEditPage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-complexity/1/edit');

        $this->assertContains('id="create_name"', $crawler->html());
        $this->assertContains('name="create[name]"', $crawler->html());
        $this->assertContains('id="create_sequence"', $crawler->html());
        $this->assertContains('name="create[sequence]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());
        $this->assertContains('class="zmdi zmdi-delete"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnEditPage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-complexity/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[name]'] = '';
        $form['create[sequence]'] = '';

        $crawler = $this->client->submit($form);

        $this->assertContains('The name field should not be blank', $crawler->html());
        $this->assertContains('The sequence field should not be blank', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testNameIsUniqueOnEditPage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-complexity/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[name]'] = 'project-complexity2';

        $crawler = $this->client->submit($form);

        $this->assertContains('That name is taken', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testSequenceIsValidOnEditPage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-complexity/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[sequence]'] = 'sequence';

        $crawler = $this->client->submit($form);

        $this->assertContains('The sequence field should contain numbers greater than or equal to 0', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEditAction()
    {
        $this->login();

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-complexity/2/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[name]'] = 'project-complexity2';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Project complexity successfully edited!', $this->client->getResponse()->getContent());
    }

    public function testDataTableOnListPage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-complexity/list');

        $this->assertEquals(1, $crawler->filter('#data-table-command')->count());
        $this->assertContains('data-column-id="id"', $crawler->html());
        $this->assertContains('data-column-id="name"', $crawler->html());
        $this->assertContains('data-column-id="sequence"', $crawler->html());
        $this->assertContains('data-column-id="createdAt"', $crawler->html());
        $this->assertContains('data-column-id="updatedAt"', $crawler->html());
        $this->assertContains('data-column-id="commands"', $crawler->html());
        $this->assertEquals(1, $crawler->filter('.zmdi-plus')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testTableStructureOnShowAction()
    {
        $this->login();

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-complexity/1/show');

        $this->assertEquals(1, $crawler->filter('.dropdown-menu-right')->count());
        $this->assertEquals(1, $crawler->filter('.table-responsive')->count());
        $this->assertEquals(1, $crawler->filter('.table-striped')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
