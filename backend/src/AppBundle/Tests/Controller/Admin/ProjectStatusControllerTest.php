<?php

namespace AppBundle\Tests\Controller\Admin;

use AppBundle\Entity\ProjectStatus;
use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\Translator;

class ProjectStatusControllerTest extends BaseController
{
    /**
     * @var Translator
     */
    protected static $translation;

    public static function setUpBeforeClass()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        self::$translation = $kernel->getContainer()->get('translator');
    }

    public function testFormIsDisplayedOnCreatePage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-status/create');

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
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-status/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['create[sequence]'] = '';

        $crawler = $this->client->submit($form);

        $this->assertContains('The name field should not be blank', $crawler->html());
        $this->assertContains(self::$translation->trans('not_blank.name', [], 'validators'), $crawler->html());
        $this->assertContains(self::$translation->trans('not_blank.sequence', [], 'validators'), $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testNameIsUniqueOnCreatePage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-status/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['create[name]'] = 'project-status1';

        $crawler = $this->client->submit($form);

        $this->assertContains(self::$translation->trans('unique.name', [], 'validators'), $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testSequenceIsValidOnCreatePage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-status/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['create[name]'] = 'project-status';
        $form['create[sequence]'] = -11;

        $crawler = $this->client->submit($form);

        $this->assertContains(self::$translation->trans('greater_than_or_equal.sequence', [], 'validators'), $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateAction()
    {
        $this->login();

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-status/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['create[name]'] = 'project-status7';
        $form['create[sequence]'] = 441;

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->client->followRedirect();
        $this->assertContains(self::$translation->trans('success.project_status.create', [], 'flashes'), $this->client->getResponse()->getContent());
    }

    public function testDeleteAction()
    {
        $this->login();

        $projectStatus = (new ProjectStatus())
            ->setName('project-status4')
            ->setCode('project-status4')
            ->setSequence('1')
        ;
        $this->em->persist($projectStatus);
        $this->em->flush();

        $this->client->request(Request::METHOD_GET, sprintf('/admin/project-status/%d/delete', $projectStatus->getId()));
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains(self::$translation->trans('failed.project_status.delete.generic', [], 'flashes'), $this->client->getResponse()->getContent());
    }

    public function testFormIsDisplayedOnEditPage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-status/1/edit');

        $this->assertContains('id="create_name"', $crawler->html());
        $this->assertContains('name="create[name]"', $crawler->html());
        $this->assertContains('id="create_sequence"', $crawler->html());
        $this->assertContains('name="create[sequence]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());
        $this->assertNotContains('class="zmdi zmdi-delete"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnEditPage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-status/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[name]'] = '';
        $form['create[sequence]'] = '';

        $crawler = $this->client->submit($form);

        $this->assertContains(self::$translation->trans('not_blank.name', [], 'validators'), $crawler->html());
        $this->assertContains(self::$translation->trans('not_blank.sequence', [], 'validators'), $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testNameIsUniqueOnEditPage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-status/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[name]'] = 'project-status2';

        $crawler = $this->client->submit($form);

        $this->assertContains(self::$translation->trans('unique.name', [], 'validators'), $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testSequenceIsValidOnEditPage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-status/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[sequence]'] = -11;

        $crawler = $this->client->submit($form);

        $this->assertContains(self::$translation->trans('greater_than_or_equal.sequence', [], 'validators'), $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEditAction()
    {
        $this->login();

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-status/2/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[name]'] = 'project-status2';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains(self::$translation->trans('success.project_status.edit', [], 'flashes'), $this->client->getResponse()->getContent());
    }

    public function testDataTableOnListPage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-status/list');

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

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-status/1/show');

        $this->assertEquals(1, $crawler->filter('.dropdown-menu-right')->count());
        $this->assertEquals(1, $crawler->filter('.table-responsive')->count());
        $this->assertEquals(1, $crawler->filter('.table-striped')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
