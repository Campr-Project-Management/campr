<?php

namespace AppBundle\Tests\Controller\Admin;

use AppBundle\Entity\Currency;
use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectUser;
use AppBundle\Entity\Company;
use Component\Currency\CurrencyInterface;
use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProjectControllerTest extends BaseController
{
    public function testFormIsDisplayedOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project/create');

        $this->assertContains('id="create_name"', $crawler->html());
        $this->assertContains('name="create[name]"', $crawler->html());
        $this->assertContains('id="create_number"', $crawler->html());
        $this->assertContains('name="create[number]"', $crawler->html());
        $this->assertContains('id="create_logoFile_file"', $crawler->html());
        $this->assertContains('name="create[logoFile][file]"', $crawler->html());
        $this->assertContains('id="create_programme"', $crawler->html());
        $this->assertContains('name="create[programme]"', $crawler->html());
        $this->assertContains('id="create_company"', $crawler->html());
        $this->assertContains('name="create[company]"', $crawler->html());
        $this->assertContains('id="create_projectComplexity"', $crawler->html());
        $this->assertContains('name="create[projectComplexity]"', $crawler->html());
        $this->assertContains('id="create_projectCategory"', $crawler->html());
        $this->assertContains('name="create[projectCategory]"', $crawler->html());
        $this->assertContains('id="create_projectScope"', $crawler->html());
        $this->assertContains('name="create[projectScope]"', $crawler->html());
        $this->assertContains('id="create_status"', $crawler->html());
        $this->assertContains('name="create[status]"', $crawler->html());
        $this->assertContains('id="create_portfolio"', $crawler->html());
        $this->assertContains('name="create[portfolio]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

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
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');
        $project = $this
            ->em
            ->getRepository(Project::class)
            ->findOneByName('project1')
        ;

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['create[name]'] = 'project';
        $form['create[number]'] = $project->getNumber();

        $crawler = $this->client->submit($form);

        $this->assertContains('That number is taken', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project/create');

        $currency = $this->findCurrencyByCode('EUR');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['create[name]'] = 'project3';
        $form['create[number]'] = 'project-number-3';
        $form['create[company]'] = 1;
        $form['create[currency]'] = $currency->getId();

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Project successfully created!', $this->client->getResponse()->getContent());

        $project = $this
            ->em
            ->getRepository(Project::class)
            ->findOneByName('project3')
        ;
        $projectUser = $this
            ->em
            ->getRepository(ProjectUser::class)
            ->findOneBy([
                'user' => $this->user,
                'project' => $project,
            ])
        ;
        foreach ($project->getWorkPackages() as $workPackage) {
            $this->em->remove($workPackage);
        }
        $this->em->remove($projectUser);
        $this->em->remove($project);
        $this->em->flush();
    }

    public function testDeleteAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $company = $this
            ->em
            ->getRepository(Company::class)
            ->find(1)
        ;

        $project = (new Project())
            ->setName('project4')
            ->setNumber('project-number-4')
            ->setCompany($company)
        ;
        $this->em->persist($project);
        $this->em->flush();

        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/admin/project/%d/edit', $project->getId()));

        $link = $crawler->selectLink('Delete')->link();
        $this->client->click($link);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Project successfully deleted!', $this->client->getResponse()->getContent());
    }

    public function testFormIsDisplayedOnEditPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project/1/edit');

        $this->assertContains('id="create_name"', $crawler->html());
        $this->assertContains('name="create[name]"', $crawler->html());
        $this->assertContains('id="create_number"', $crawler->html());
        $this->assertContains('name="create[number]"', $crawler->html());
        $this->assertContains('id="create_logoFile_file"', $crawler->html());
        $this->assertContains('name="create[logoFile][file]"', $crawler->html());
        $this->assertContains('id="create_programme"', $crawler->html());
        $this->assertContains('name="create[programme]"', $crawler->html());
        $this->assertContains('id="create_company"', $crawler->html());
        $this->assertContains('name="create[company]"', $crawler->html());
        $this->assertContains('id="create_projectComplexity"', $crawler->html());
        $this->assertContains('name="create[projectComplexity]"', $crawler->html());
        $this->assertContains('id="create_projectCategory"', $crawler->html());
        $this->assertContains('name="create[projectCategory]"', $crawler->html());
        $this->assertContains('id="create_projectScope"', $crawler->html());
        $this->assertContains('name="create[projectScope]"', $crawler->html());
        $this->assertContains('id="create_status"', $crawler->html());
        $this->assertContains('name="create[status]"', $crawler->html());
        $this->assertContains('id="create_portfolio"', $crawler->html());
        $this->assertContains('name="create[portfolio]"', $crawler->html());
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
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[name]'] = '';
        $form['create[number]'] = '';

        $crawler = $this->client->submit($form);

        $this->assertContains('The name field should not be blank', $crawler->html());
        $this->assertContains('The number field should not be blank', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testNumberIsUniqueOnEditPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');
        $project = $this
            ->em
            ->getRepository(Project::class)
            ->findOneByName('project2')
        ;

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[name]'] = 'project';
        $form['create[number]'] = $project->getNumber();

        $crawler = $this->client->submit($form);

        $this->assertContains('That number is taken', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEditAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project/2/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[name]'] = 'project2';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Project successfully edited!', $this->client->getResponse()->getContent());
    }

    public function testDataTableOnListPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

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
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project/1/show');

        $this->assertEquals(1, $crawler->filter('.dropdown-menu-right')->count());
        $this->assertEquals(1, $crawler->filter('.table-responsive')->count());
        $this->assertEquals(1, $crawler->filter('.table-striped')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testChatAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project/1/chat');
        $this->assertEquals(1, $crawler->filter('section#content')->count());
        $this->assertContains('<div class="container container-alt">', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testChatMessagesAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $this->client->request(Request::METHOD_GET, 'admin/project/1/chat/1/messages');
        $response = $this->client->getResponse();
        $html = json_decode($response->getContent(), true);
        $this->assertContains('<div class="mbl-messages c-overflow">', $html);
        $this->assertEquals(3, substr_count($html, '<a class="user-chat" data-id='));
        $this->assertEquals(3, substr_count($html, '<div class="mblm-item mblm-item-left">'));
    }

    public function testChatPrivateMessagesAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->getUserByUsername('superadmin'));
        $this->assertNotNull($this->user, 'User not found');

        $this->client->request(Request::METHOD_GET, 'admin/project/1/chat/5/private-messages');
        $response = $this->client->getResponse();
        $html = json_decode($response->getContent(), true);
        $this->assertContains('<div class="mbl-messages c-overflow">', $html);
        $this->assertEquals(4, substr_count($html, '<div class="mblm-item mblm-item-right">'));
    }

    public function testDeletePrivateMessagesAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->getUserByUsername('superadmin'));
        $this->assertNotNull($this->user, 'User not found');

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
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $this->client->request(Request::METHOD_GET, '/admin/project/1/participants');

        $this->assertEquals(json_encode($expected), $this->client->getResponse()->getContent());
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
                        'roles' => ['manager'],
                    ],
                    [
                        'id' => 4,
                        'username' => 'user4',
                        'roles' => ['sponsor'],
                    ],
                    [
                        'id' => 5,
                        'username' => 'user5',
                        'roles' => ['team-member'],
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
            ->findOneBy(['code' => $code])
        ;

        $this->assertNotNull($currency, sprintf('Currency "EUR" not found'));

        return $currency;
    }
}
