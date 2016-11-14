<?php

namespace AppBundle\Tests\Controller\Admin;

use AppBundle\Entity\Day;
use AppBundle\Entity\WorkingTime;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WorkingTimeControllerTest extends BaseController
{
    public function testFormIsDisplayedOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/working-time/create');

        $this->assertContains('id="create_day"', $crawler->html());
        $this->assertContains('name="create[day]"', $crawler->html());
        $this->assertContains('id="create_fromTime"', $crawler->html());
        $this->assertContains('name="create[fromTime]"', $crawler->html());
        $this->assertContains('id="create_toTime"', $crawler->html());
        $this->assertContains('name="create[toTime]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/working-time/create');

        $form = $crawler->filter('#create-form')->first()->form();

        $crawler = $this->client->submit($form);

        $this->assertContains('The day field should not be blank', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $day = (new Day())
            ->setType(20)
            ->setWorking(5)
        ;
        $this->em->persist($day);
        $this->em->flush();

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/working-time/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['create[day]'] = $day->getId();

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Working time successfully created!', $this->client->getResponse()->getContent());

        $workingTime = $this
            ->em
            ->getRepository(WorkingTime::class)
            ->findOneBy([
                'day' => $day->getId(),
            ])
        ;
        $this->em->remove($workingTime);

        $day = $this
            ->em
            ->getRepository(Day::class)
            ->findOneBy([
                'type' => '20',
            ])
        ;
        $this->em->remove($day);
        $this->em->flush();
    }

    public function testDeleteAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $day = (new Day())
            ->setType(20)
            ->setWorking(5)
        ;
        $this->em->persist($day);

        $workingTime = (new WorkingTime())
            ->setDay($day)
        ;
        $this->em->persist($workingTime);
        $this->em->flush();

        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/admin/working-time/%d/edit', $workingTime->getId()));

        $link = $crawler->selectLink('Delete')->link();
        $this->client->click($link);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Working time successfully deleted!', $this->client->getResponse()->getContent());

        $day = $this
            ->em
            ->getRepository(Day::class)
            ->findOneBy([
                'type' => '20',
            ])
        ;
        $this->em->remove($day);
        $this->em->flush();
    }

    public function testFormIsDisplayedOnEditPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/working-time/1/edit');

        $this->assertContains('id="create_day"', $crawler->html());
        $this->assertContains('name="create[day]"', $crawler->html());
        $this->assertContains('id="create_fromTime"', $crawler->html());
        $this->assertContains('name="create[fromTime]"', $crawler->html());
        $this->assertContains('id="create_toTime"', $crawler->html());
        $this->assertContains('name="create[toTime]"', $crawler->html());
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
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/working-time/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[day]'] = '';

        $crawler = $this->client->submit($form);

        $this->assertContains('The day field should not be blank', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEditAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $day = (new Day())
            ->setType(20)
            ->setWorking(5)
        ;
        $this->em->persist($day);

        $workingTime = (new WorkingTime())
            ->setDay($day)
        ;
        $this->em->persist($workingTime);
        $this->em->flush();

        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/admin/working-time/%d/edit', $workingTime->getId()));

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[day]'] = $day->getId();

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Working time successfully edited!', $this->client->getResponse()->getContent());

        $workingTime = $this
            ->em
            ->getRepository(WorkingTime::class)
            ->findOneBy([
                'day' => $day->getId(),
            ])
        ;
        $this->em->remove($workingTime);

        $day = $this
            ->em
            ->getRepository(Day::class)
            ->findOneBy([
                'type' => '20',
            ])
        ;
        $this->em->remove($day);
        $this->em->flush();
    }

    public function testDataTableOnListPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/working-time/list');

        $this->assertEquals(1, $crawler->filter('#data-table-command')->count());
        $this->assertContains('data-column-id="id"', $crawler->html());
        $this->assertContains('data-column-id="day"', $crawler->html());
        $this->assertContains('data-column-id="fromTime"', $crawler->html());
        $this->assertContains('data-column-id="toTime"', $crawler->html());
        $this->assertContains('data-column-id="commands"', $crawler->html());
        $this->assertEquals(1, $crawler->filter('.zmdi-plus')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testTableStructureOnShowAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/working-time/1/show');

        $this->assertEquals(1, $crawler->filter('.dropdown-menu-right')->count());
        $this->assertEquals(1, $crawler->filter('.table-responsive')->count());
        $this->assertEquals(1, $crawler->filter('.table-striped')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
