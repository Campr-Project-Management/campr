<?php

namespace MainBundle\Tests\Controller\Admin;

use AppBundle\Entity\PaymentMethod;
use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PaymentMethodControllerTest extends BaseController
{
    public function testFormIsDisplayedOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/payment-method/create');

        $this->assertContains('id="create_name"', $crawler->html());
        $this->assertContains('name="create[name]"', $crawler->html());
        $this->assertContains('id="create_token"', $crawler->html());
        $this->assertContains('name="create[token]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/payment-method/create');

        $form = $crawler->filter('#create-payment-method')->first()->form();
        $crawler = $this->client->submit($form);

        $this->assertContains('Please enter a payment method name!', $crawler->html());
        $this->assertContains('The token field should not be blank', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/payment-method/create');

        $form = $crawler->filter('#create-payment-method')->first()->form();
        $form['create[name]'] = 'payment_method_test';
        $form['create[token]'] = 'token_test';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Payment method successfully created!', $this->client->getResponse()->getContent());

        $paymentMethod = $this
            ->em
            ->getRepository(PaymentMethod::class)
            ->findOneBy([
                'name' => 'payment_method_test',
                'token' => 'token_test',
            ])
        ;
        $this->em->remove($paymentMethod);
        $this->em->flush();
    }

    public function testDeleteAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $paymentMethod = (new PaymentMethod())
            ->setName('pmethod')
            ->setToken('token')
        ;
        $this->em->persist($paymentMethod);
        $this->em->flush();

        $this->client->request(Request::METHOD_GET, sprintf('/admin/payment-method/%d/delete', $paymentMethod->getId()));
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->client->followRedirect();

        $this->assertContains('Payment method successfully deleted!', $this->client->getResponse()->getContent());
    }

    public function testFormIsDisplayedOnEditPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/payment-method/1/edit');

        $this->assertContains('id="create_name"', $crawler->html());
        $this->assertContains('name="create[name]"', $crawler->html());
        $this->assertContains('id="create_token"', $crawler->html());
        $this->assertContains('name="create[token]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnEditPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/payment-method/1/edit');

        $form = $crawler->filter('#edit-payment-method')->first()->form();
        $form['create[name]'] = '';
        $form['create[token]'] = '';

        $crawler = $this->client->submit($form);

        $this->assertContains('Please enter a payment method name!', $crawler->html());
        $this->assertContains('The token field should not be blank', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEditAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/payment-method/1/edit');

        $form = $crawler->filter('#edit-payment-method')->first()->form();
        $form['create[name]'] = 'new_name';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Payment method successfully edited!', $this->client->getResponse()->getContent());
    }

    public function testListActionPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/payment-method/list');

        $this->assertEquals(1, $crawler->filter('table.table-condensed.table-responsive')->count());
        $this->assertEquals(5, $crawler->filter('table.table-condensed.table-responsive th')->count());
        $this->assertEquals(1, $crawler->filter('.glyphicon-plus')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
