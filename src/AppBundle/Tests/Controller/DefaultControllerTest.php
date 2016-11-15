<?php

namespace AppBundle\Tests\Controller;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultControllerTest extends BaseController
{
    public function testFormIsDisplayedOnLoginPage()
    {
        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/login');

        $this->assertContains('id="email"', $crawler->html());
        $this->assertContains('name="email"', $crawler->html());
        $this->assertContains('id="password"', $crawler->html());
        $this->assertContains('name="password"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testInvalidCredentials()
    {
        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/login');

        $form = $crawler->filter('#login-form')->first()->form();
        $form['email'] = '';
        $form['password'] = '';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->client->followRedirect();

        $this->assertContains('Invalid credentials.', $this->client->getResponse()->getContent());
    }

    public function testLoginSuccessfully()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);

        $crawler = $this->client->request(Request::METHOD_GET, '/login');

        $form = $crawler->filter('#login-form')->first()->form();
        $form['email'] = 'testuser@trisoft.ro';
        $form['password'] = 'Password1';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->client->followRedirect();

        $this->assertContains('Welcome', $this->client->getResponse()->getContent());
    }

    public function testLogoutSuccessfully()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $this->logout();
        $this->assertContains('Welcome', $this->client->getResponse()->getContent());
    }
}
