<?php

namespace AppBundle\Tests\Controller\Admin;

use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectUser;
use AppBundle\Entity\Company;
use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProjectUserControllerTest extends BaseController
{
    public function testFormIsDisplayedOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-user/create');

        $this->assertContains('id="create_user"', $crawler->html());
        $this->assertContains('name="create[user]"', $crawler->html());
        $this->assertContains('id="create_project"', $crawler->html());
        $this->assertContains('name="create[project]"', $crawler->html());
        $this->assertContains('id="create_projectRoles"', $crawler->html());
        $this->assertContains('name="create[projectRoles][]"', $crawler->html());
        $this->assertContains('id="create_projectDepartments"', $crawler->html());
        $this->assertContains('name="create[projectDepartments][]"', $crawler->html());
        $this->assertContains('id="create_projectTeam"', $crawler->html());
        $this->assertContains('name="create[projectTeam]"', $crawler->html());
        $this->assertContains('id="create_showInResources"', $crawler->html());
        $this->assertContains('name="create[showInResources]"', $crawler->html());
        $this->assertContains('id="create_showInRasci"', $crawler->html());
        $this->assertContains('name="create[showInRasci]"', $crawler->html());
        $this->assertContains('id="create_showInOrg"', $crawler->html());
        $this->assertContains('name="create[showInOrg]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-user/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $crawler = $this->client->submit($form);

        $this->assertContains('The name field should not be blank. Choose one user', $crawler->html());
        $this->assertContains('The project field should not be blank. Choose one project', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $company = (new Company())
            ->setName('company4')
        ;
        $this->em->persist($company);

        $project = (new Project())
            ->setName('project4')
            ->setNumber('project-number-4')
            ->setCompany($company)
        ;
        $this->em->persist($project);
        $this->em->flush();

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-user/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['create[user]'] = $this->user->getId();
        $form['create[project]'] = $project->getId();

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Project user successfully created!', $this->client->getResponse()->getContent());

        $projectUser = $this
            ->em
            ->getRepository(ProjectUser::class)
            ->findOneBy([
                'user' => $this->user,
            ])
        ;
        $this->em->remove($projectUser);

        $project = $this
            ->em
            ->getRepository(Project::class)
            ->findOneBy([
                'number' => 'project-number-4',
            ])
        ;
        $this->em->remove($project);

        $company = $this
            ->em
            ->getRepository(Company::class)
            ->findOneBy([
                'name' => 'company4',
            ])
        ;
        $this->em->remove($company);

        $this->em->flush();
    }

    public function testDeleteAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $company = (new Company())
            ->setName('company4')
        ;
        $this->em->persist($company);

        $project = (new Project())
            ->setName('project4')
            ->setNumber('project-number-4')
            ->setCompany($company)
        ;
        $this->em->persist($project);

        $projectUser = (new  ProjectUser())
            ->setUser($this->user)
            ->setProject($project)
        ;
        $this->em->persist($projectUser);
        $this->em->flush();

        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/admin/project-user/%d/edit', $projectUser->getId()));

        $link = $crawler->selectLink('Delete')->link();
        $this->client->click($link);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Project user successfully deleted!', $this->client->getResponse()->getContent());

        $project = $this
            ->em
            ->getRepository(Project::class)
            ->findOneBy([
                'number' => 'project-number-4',
            ])
        ;
        $this->em->remove($project);

        $company = $this
            ->em
            ->getRepository(Company::class)
            ->findOneBy([
                'name' => 'company4',
            ])
        ;
        $this->em->remove($company);

        $this->em->flush();
    }

    public function testFormIsDisplayedOnEditPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-user/1/edit');

        $this->assertContains('id="create_user"', $crawler->html());
        $this->assertContains('name="create[user]"', $crawler->html());
        $this->assertContains('id="create_project"', $crawler->html());
        $this->assertContains('name="create[project]"', $crawler->html());
        $this->assertContains('id="create_projectRoles"', $crawler->html());
        $this->assertContains('name="create[projectRoles][]"', $crawler->html());
        $this->assertContains('id="create_projectDepartments"', $crawler->html());
        $this->assertContains('name="create[projectDepartments][]"', $crawler->html());
        $this->assertContains('id="create_projectTeam"', $crawler->html());
        $this->assertContains('name="create[projectTeam]"', $crawler->html());
        $this->assertContains('id="create_showInResources"', $crawler->html());
        $this->assertContains('name="create[showInResources]"', $crawler->html());
        $this->assertContains('id="create_showInRasci"', $crawler->html());
        $this->assertContains('name="create[showInRasci]"', $crawler->html());
        $this->assertContains('id="create_showInOrg"', $crawler->html());
        $this->assertContains('name="create[showInOrg]"', $crawler->html());
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
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-user/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[user]'] = '';
        $form['create[project]'] = '';

        $crawler = $this->client->submit($form);

        $this->assertContains('The name field should not be blank. Choose one user', $crawler->html());
        $this->assertContains('The project field should not be blank. Choose one project', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEditAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $company = (new Company())
            ->setName('company4')
        ;
        $this->em->persist($company);

        $project = (new Project())
            ->setName('project4')
            ->setNumber('project-number-4')
            ->setCompany($company)
        ;
        $this->em->persist($project);

        $projectUser = (new  ProjectUser())
            ->setUser($this->user)
            ->setProject($project)
        ;
        $this->em->persist($projectUser);
        $this->em->flush();

        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/admin/project-user/%d/edit', $projectUser->getId()));

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[user]'] = $this->user->getId();

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Project user successfully edited!', $this->client->getResponse()->getContent());

        $projectUser = $this
            ->em
            ->getRepository(ProjectUser::class)
            ->findOneBy([
                'user' => $this->user,
            ])
        ;
        $this->em->remove($projectUser);

        $project = $this
            ->em
            ->getRepository(Project::class)
            ->findOneBy([
                'number' => 'project-number-4',
            ])
        ;
        $this->em->remove($project);

        $company = $this
            ->em
            ->getRepository(Company::class)
            ->findOneBy([
                'name' => 'company4',
            ])
        ;
        $this->em->remove($company);

        $this->em->flush();
    }

    public function testDataTableOnListPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-user/list');

        $this->assertEquals(1, $crawler->filter('#data-table-command')->count());
        $this->assertContains('data-column-id="id"', $crawler->html());
        $this->assertContains('data-column-id="userFullName"', $crawler->html());
        $this->assertContains('data-column-id="projectName"', $crawler->html());
        $this->assertContains('data-column-id="projectTeamName"', $crawler->html());
        $this->assertContains('data-column-id="commands"', $crawler->html());
        $this->assertEquals(1, $crawler->filter('.zmdi-plus')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testTableStructureOnShowAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-user/1/show');

        $this->assertEquals(1, $crawler->filter('.dropdown-menu-right')->count());
        $this->assertEquals(1, $crawler->filter('.table-responsive')->count());
        $this->assertEquals(1, $crawler->filter('.table-striped')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
