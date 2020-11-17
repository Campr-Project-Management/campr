<?php

namespace AppBundle\Tests\Controller\Admin;

use AppBundle\Entity\Currency;
use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectRole;
use AppBundle\Entity\ProjectUser;
use Component\Currency\Model\CurrencyInterface;
use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProjectControllerTest extends BaseController
{
    public function testFormIsDisplayedOnCreatePage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project/create');

        $this->assertContains('id="project_name"', $crawler->html());
        $this->assertContains('name="project[name]"', $crawler->html());
        $this->assertContains('id="project_number"', $crawler->html());
        $this->assertContains('name="project[number]"', $crawler->html());
        $this->assertContains('id="project_logoFile_file"', $crawler->html());
        $this->assertContains('name="project[logoFile][file]"', $crawler->html());
        $this->assertContains('id="project_programme"', $crawler->html());
        $this->assertContains('name="project[programme]"', $crawler->html());
        $this->assertContains('id="project_company"', $crawler->html());
        $this->assertContains('name="project[company]"', $crawler->html());
        $this->assertContains('id="project_projectComplexity"', $crawler->html());
        $this->assertContains('name="project[projectComplexity]"', $crawler->html());
        $this->assertContains('id="project_projectCategory"', $crawler->html());
        $this->assertContains('name="project[projectCategory]"', $crawler->html());
        $this->assertContains('id="project_projectScope"', $crawler->html());
        $this->assertContains('name="project[projectScope]"', $crawler->html());
        $this->assertContains('id="project_status"', $crawler->html());
        $this->assertContains('name="project[status]"', $crawler->html());
        $this->assertContains('id="project_portfolio"', $crawler->html());
        $this->assertContains('name="project[portfolio]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnCreatePage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project/create');

        $form = $crawler->filter('#create-form')->first()->form();

        $crawler = $this->client->submit($form);

        $this->assertContains('The name field should not be blank', $crawler->html());
        $this->assertContains('The number field should not be blank', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testNumberIsUniqueOnCreatePage()
    {
        $this->login();
        $project = $this
            ->em
            ->getRepository(Project::class)
            ->find(1);

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['project[name]'] = 'project';
        $form['project[number]'] = $project->getNumber();

        $crawler = $this->client->submit($form);

        $this->assertContains('That number is taken', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateAction()
    {
        $this->markTestSkipped();
        $this->login();

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project/create');

        $currency = $this->findCurrencyByCode('EUR');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['project[name]'] = 'project3';
        $form['project[number]'] = 'project-number-3';
        $form['project[company]'] = 1;
        $form['project[currency]'] = $currency->getId();

        try {
            $this->client->submit($form);
            $response = $this->client->getResponse();
        } finally {
            $this->assertTrue($response->isRedirection(), $response->getContent());

            $this->client->followRedirect();
            $this->assertContains('Project successfully created!', $this->client->getResponse()->getContent());

            $project = $this
                ->em
                ->getRepository(Project::class)
                ->findOneByName('project3');
            if ($project) {
                $this->em->remove($project);
            }

            $projectUser = $this
                ->em
                ->getRepository(ProjectUser::class)
                ->findOneBy(
                    [
                        'user' => $this->user,
                        'project' => $project,
                    ]
                );
            if ($projectUser) {
                $this->em->remove($projectUser);
            }

            $this->em->flush();
        }
    }

    public function testDeleteAction()
    {
        $this->login();

        $project = (new Project())
            ->setName('project4')
            ->setNumber('project-number-4')
            ->setCompany('ACME');
        $this->em->persist($project);
        $this->em->flush();

        $this->client->request(Request::METHOD_GET, sprintf('/admin/project/%d/delete', $project->getId()));
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Project successfully deleted!', $this->client->getResponse()->getContent());
    }

    public function testFormIsDisplayedOnEditPage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project/1/edit');

        $this->assertContains('id="project_name"', $crawler->html());
        $this->assertContains('name="project[name]"', $crawler->html());
        $this->assertContains('id="project_number"', $crawler->html());
        $this->assertContains('name="project[number]"', $crawler->html());
        $this->assertContains('id="project_logoFile_file"', $crawler->html());
        $this->assertContains('name="project[logoFile][file]"', $crawler->html());
        $this->assertContains('id="project_programme"', $crawler->html());
        $this->assertContains('name="project[programme]"', $crawler->html());
        $this->assertContains('id="project_company"', $crawler->html());
        $this->assertContains('name="project[company]"', $crawler->html());
        $this->assertContains('id="project_projectComplexity"', $crawler->html());
        $this->assertContains('name="project[projectComplexity]"', $crawler->html());
        $this->assertContains('id="project_projectCategory"', $crawler->html());
        $this->assertContains('name="project[projectCategory]"', $crawler->html());
        $this->assertContains('id="project_projectScope"', $crawler->html());
        $this->assertContains('name="project[projectScope]"', $crawler->html());
        $this->assertContains('id="project_status"', $crawler->html());
        $this->assertContains('name="project[status]"', $crawler->html());
        $this->assertContains('id="project_portfolio"', $crawler->html());
        $this->assertContains('name="project[portfolio]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());
        $this->assertContains('class="zmdi zmdi-delete"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnEditPage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['project[name]'] = '';
        $form['project[number]'] = '';

        $crawler = $this->client->submit($form);

        $this->assertContains('The name field should not be blank', $crawler->html());
        $this->assertContains('The number field should not be blank', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testNumberIsUniqueOnEditPage()
    {
        $this->login();
        $project = $this
            ->em
            ->getRepository(Project::class)
            ->findOneByName('project2');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['project[name]'] = 'project';
        $form['project[number]'] = $project->getNumber();

        $crawler = $this->client->submit($form);

        $this->assertContains('That number is taken', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEditAction()
    {
        $this->login();

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project/2/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['project[name]'] = 'project2';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Project successfully edited!', $this->client->getResponse()->getContent());
    }

    public function testDataTableOnListPage()
    {
        $this->login();

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project/list');

        $this->assertEquals(1, $crawler->filter('#data-table-command')->count());
        $this->assertContains('data-column-id="id"', $crawler->html());
        $this->assertContains('data-column-id="name"', $crawler->html());
        $this->assertContains('data-column-id="number"', $crawler->html());
        $this->assertContains('data-column-id="projectCategoryName"', $crawler->html());
        $this->assertContains('data-column-id="statusName"', $crawler->html());
        $this->assertContains('data-column-id="portfolioName"', $crawler->html());
        $this->assertContains('data-column-id="commands"', $crawler->html());
        $this->assertEquals(1, $crawler->filter('.zmdi-plus')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testTableStructureOnShowAction()
    {
        $this->login();

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project/1/show');

        $this->assertEquals(1, $crawler->filter('.dropdown-menu-right')->count());
        $this->assertEquals(1, $crawler->filter('.table-responsive')->count());
        $this->assertEquals(1, $crawler->filter('.table-striped')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testChatAction()
    {
        $this->login();

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project/1/chat');
        $this->assertEquals(1, $crawler->filter('section#content')->count());
        $this->assertContains('<div class="container container-alt">', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testChatMessagesAction()
    {
        $this->login();

        $this->client->request(Request::METHOD_GET, 'admin/project/1/chat/1/messages');
        $response = $this->client->getResponse();
        $html = json_decode($response->getContent(), true);
        $this->assertContains('<div class="mbl-messages c-overflow">', $html);
        $this->assertEquals(3, substr_count($html, '<div class="mblm-item mblm-item-right">'));
    }

    public function testChatPrivateMessagesAction()
    {
        $this->login();

        $this->client->request(Request::METHOD_GET, 'admin/project/1/chat/5/private-messages');
        $response = $this->client->getResponse();
        $html = json_decode($response->getContent(), true);
        $this->assertContains('<div class="mbl-messages c-overflow">', $html);
        $this->assertEquals(4, substr_count($html, '<div class="mblm-item mblm-item-right">'));
    }

    public function testDeletePrivateMessagesAction()
    {
        $this->login();

        $this->client->request(Request::METHOD_GET, 'admin/project/1/chat/5/delete-private-messages');
        $html = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertSame(['success' => 'Messages successfully deleted!'], $html);
    }

    /**
     * @dataProvider getDataForTestParticipantsAction
     *
     * @param mixed $expected
     */
    public function testParticipantsAction($expected)
    {
        $this->login();

        $this->client->request(Request::METHOD_GET, '/admin/project/1/participants');

        $actual = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals($expected, $actual);
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @return array
     */
    public function getDataForTestParticipantsAction()
    {
        return [
            [
                [
                    [
                        'id' => 3,
                        'username' => 'user3',
                        'roles' => [ProjectRole::ROLE_MANAGER],
                    ],
                    [
                        'id' => 4,
                        'username' => 'user4',
                        'roles' => [ProjectRole::ROLE_SPONSOR],
                    ],
                    [
                        'id' => 5,
                        'username' => 'user5',
                        'roles' => [ProjectRole::ROLE_TEAM_MEMBER],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param string $code
     *
     * @return CurrencyInterface
     */
    private function findCurrencyByCode(string $code): CurrencyInterface
    {
        $currency = $this
            ->em
            ->getRepository(Currency::class)
            ->findOneBy(['code' => $code]);

        $this->assertNotNull($currency, sprintf('Currency "EUR" not found'));

        return $currency;
    }
}
