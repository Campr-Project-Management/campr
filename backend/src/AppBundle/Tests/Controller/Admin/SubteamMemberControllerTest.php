<?php

namespace AppBundle\Tests\Controller\Admin;

use AppBundle\Entity\Subteam;
use AppBundle\Entity\SubteamMember;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use MainBundle\Tests\Controller\BaseController;

class SubteamMemberControllerTest extends BaseController
{
    public function testFormIsDisplayedOnCreatePage()
    {
        $this->user = $this->createUser('subteam_user1', 'subteam_user1@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/subteam-member/create');

        $this->assertContains('id="create_user"', $crawler->html());
        $this->assertContains('name="create[user]"', $crawler->html());
        $this->assertContains('id="create_subteam"', $crawler->html());
        $this->assertContains('name="create[subteam]"', $crawler->html());
        $this->assertContains('id="create_isLead"', $crawler->html());
        $this->assertContains('name="create[isLead]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnCreatePage()
    {
        $this->user = $this->createUser('subteam_user2', 'subteam_user2@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/subteam-member/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $crawler = $this->client->submit($form);

        $this->assertContains('You must select a user.', $crawler->html());
        $this->assertContains('You must select a subteam.', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateAction()
    {
        $this->user = $this->createUser('subteam_user3', 'subteam_user3@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Subteam $subteam */
        $subteam = $this->createSubteam('Generic name', 'Lorem ipsum dolor sit amet');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/subteam-member/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['create[user]'] = $this->user->getId();
        $form['create[subteam]'] = $subteam->getId();
        $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->client->followRedirect();
        $this->assertContains('Subteam Member successfully created!', $this->client->getResponse()->getContent());

        $subteam = $this
            ->em
            ->getRepository(Subteam::class)
            ->findOneBy([
                'id' => $subteam->getId(),
            ])
        ;
        $this->em->remove($subteam);
        $this->em->flush();
    }

    public function testDeleteAction()
    {
        $this->user = $this->createUser('subteam_user4', 'subteam_user4@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Subteam $subteam */
        $subteam = $this->createSubteam('Generic name', 'Lorem ipsum dolor sit amet');
        /** @var SubteamMember $subteamMember */
        $subteamMember = $this->createSubteamMember($this->user, $subteam);

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/admin/subteam-member/%d/edit', $subteamMember->getId()));

        $link = $crawler->selectLink('Delete')->link();
        $this->client->click($link);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Subteam Member successfully deleted!', $this->client->getResponse()->getContent());

        $subteam = $this
            ->em
            ->getRepository(Subteam::class)
            ->findOneBy([
                'id' => $subteam->getId(),
            ])
        ;
        $this->em->remove($subteam);
        $this->em->flush();
    }

    public function testFormIsDisplayedOnEditPage()
    {
        $this->user = $this->createUser('subteam_user5', 'subteam_user5@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Subteam $subteam */
        $subteam = $this->createSubteam('Generic name', 'Lorem ipsum dolor sit amet');
        /** @var SubteamMember $subteamMember */
        $subteamMember = $this->createSubteamMember($this->user, $subteam);

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/admin/subteam-member/%d/edit', $subteamMember->getId()));

        $this->assertContains('id="create_user"', $crawler->html());
        $this->assertContains('name="create[user]"', $crawler->html());
        $this->assertContains('id="create_subteam"', $crawler->html());
        $this->assertContains('name="create[subteam]"', $crawler->html());
        $this->assertContains('id="create_isLead"', $crawler->html());
        $this->assertContains('name="create[isLead]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());
        $this->assertContains('class="zmdi zmdi-delete"', $crawler->html());

        $this->em->remove($subteam);
        $this->em->remove($subteamMember);
        $this->em->flush();

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnEditPage()
    {
        $this->user = $this->createUser('subteam_user6', 'subteam_user6@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Subteam $subteam */
        $subteam = $this->createSubteam('Generic name', 'Lorem ipsum dolor sit amet');
        /** @var SubteamMember $subteamMember */
        $subteamMember = $this->createSubteamMember($this->user, $subteam);

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/admin/subteam-member/%d/edit', $subteamMember->getId()));

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[user]'] = '';
        $form['create[subteam]'] = '';
        $crawler = $this->client->submit($form);

        $this->assertContains('You must select a user.', $crawler->html());

        $subteam = $this
            ->em
            ->getRepository(Subteam::class)
            ->findOneBy([
                'id' => $subteam->getId(),
            ])
        ;
        $subteamMember = $this
            ->em
            ->getRepository(SubteamMember::class)
            ->findOneBy([
                'id' => $subteamMember->getId(),
            ])
        ;
        $this->em->remove($subteam);
        $this->em->remove($subteamMember);
        $this->em->flush();

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEditAction()
    {
        $this->user = $this->createUser('subteam_user7', 'subteam_user7@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Subteam $subteam */
        $subteam = $this->createSubteam('Generic name', 'Lorem ipsum dolor sit amet');
        /** @var SubteamMember $subteamMember */
        $subteamMember = $this->createSubteamMember($this->user, $subteam);

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/admin/subteam-member/%d/edit', $subteamMember->getId()));

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[user]']->select($this->user->getId());
        $form['create[subteam]']->select($subteam->getId());

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Subteam Member successfully edited!', $this->client->getResponse()->getContent());

        $subteam = $this
            ->em
            ->getRepository(Subteam::class)
            ->findOneBy([
                'id' => $subteam->getId(),
            ])
        ;
        $subteamMember = $this
            ->em
            ->getRepository(SubteamMember::class)
            ->findOneBy([
                'id' => $subteamMember->getId(),
            ])
        ;
        $this->em->remove($subteam);
        $this->em->remove($subteamMember);
        $this->em->flush();

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testDataTableOnListPage()
    {
        $this->user = $this->createUser('subteam_user8', 'subteam_user8@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/subteam-member/list');

        $this->assertEquals(1, $crawler->filter('#data-table-command')->count());
        $this->assertContains('data-column-id="id"', $crawler->html());
        $this->assertContains('data-column-id="userFullName"', $crawler->html());
        $this->assertContains('data-column-id="userEmail"', $crawler->html());
        $this->assertContains('data-column-id="commands"', $crawler->html());
        $this->assertEquals(1, $crawler->filter('.zmdi-plus')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testTableStructureOnShowAction()
    {
        $this->user = $this->createUser('subteam_user9', 'subteam_user9@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Subteam $subteam */
        $subteam = $this->createSubteam('Generic name', 'Lorem ipsum dolor sit amet');
        /** @var SubteamMember $subteamMember */
        $subteamMember = $this->createSubteamMember($this->user, $subteam);

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/admin/subteam-member/%d/show', $subteamMember->getId()));

        $this->assertEquals(1, $crawler->filter('.dropdown-menu-right')->count());
        $this->assertEquals(1, $crawler->filter('.table-responsive')->count());
        $this->assertEquals(1, $crawler->filter('.table-striped')->count());
        $this->assertEquals(5, $crawler->filter('.table-striped tr')->count());
        $this->assertContains('Generic name', $crawler->html());
        $this->assertContains('subteam_user9@trisoft.ro', $crawler->html());

        $this->em->remove($subteam);
        $this->em->remove($subteamMember);
        $this->em->flush();

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
