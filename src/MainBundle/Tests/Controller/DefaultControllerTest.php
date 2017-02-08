<?php

namespace MainBundle\Tests\Controller;

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
    }

    public function testLogoutSuccessfully()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $this->logout();
        $this->assertContains('Welcome', $this->client->getResponse()->getContent());
    }

    public function testFormIsDisplayedOnContactPage()
    {
        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/contact');

        $this->assertContains('id="contact_full_name"', $crawler->html());
        $this->assertContains('name="contact[full_name]"', $crawler->html());
        $this->assertContains('id="contact_email"', $crawler->html());
        $this->assertContains('name="contact[email]"', $crawler->html());
        $this->assertContains('id="contact_subject"', $crawler->html());
        $this->assertContains('name="contact[subject]"', $crawler->html());
        $this->assertContains('id="contact_message"', $crawler->html());
        $this->assertContains('name="contact[message]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnContactPage()
    {
        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/contact');

        $form = $crawler->filter('#contact-form')->first()->form();

        $crawler = $this->client->submit($form);

        $this->assertContains('Please enter your full name!', $crawler->html());
        $this->assertContains('The email field should not be blank', $crawler->html());
        $this->assertContains('Please enter a subject!', $crawler->html());
        $this->assertContains('Please enter a message!', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testContactSuccessfully()
    {
        $crawler = $this->client->request(Request::METHOD_GET, '/contact');

        $form = $crawler->filter('#contact-form')->first()->form();

        $form['contact[full_name]'] = 'Test test';
        $form['contact[email]'] = 'test@trisoft.ro';
        $form['contact[subject]'] = 'Subject';
        $form['contact[message]'] = 'Message';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $crawler = $this->client->followRedirect();
        $this->assertContains('Message succesfully sent!', $crawler->html());
    }
}
