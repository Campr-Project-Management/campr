<?php

namespace MainBundle\Tests\Controller;

use AppBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class BaseController extends WebTestCase
{
    /** @var Client */
    protected $client;

    /** @var ObjectManager */
    protected $em;

    /** @var User */
    protected $user;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->client = static::createClient();
        $env = $this->client->getContainer()->getParameter('kernel.environment');
        $domain = $this->client->getContainer()->getParameter('domain');
        $this->client->setServerParameters([
            'HTTP_HOST' => $env.'.'.$domain,
        ]);

        $this->em = $this->client->getContainer()->get('doctrine')->getManager();
    }

    /**
     * @param string $username
     * @param string $email
     * @param string $password
     * @param array  $roles
     *
     * @return User $user
     */
    public function createUser($username, $email, $password, array $roles)
    {
        $user = new User();
        $encoder = $this
            ->client
            ->getContainer()
            ->get('security.encoder_factory')
            ->getEncoder($user)
        ;
        $user
            ->setUsername($username)
            ->setEmail($email)
            ->setPassword($encoder->encodePassword($password, $user->getSalt()))
            ->setFirstName('FirstName')
            ->setLastName('LastName')
            ->setRoles($roles)
            ->setIsEnabled(true)
            ->setIsSuspended(false)
        ;

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    public function getUserByUsername($username)
    {
        return $this
            ->em
            ->getRepository(User::class)
            ->findOneByUsername($username)
            ;
    }

    /**
     * @param User $user
     */
    public function login(User $user)
    {
        if ($this->user) {
            $session = $this->client->getContainer()->get('session');

            $firewall = 'main';

            $token = new UsernamePasswordToken($user, null, $firewall, $user->getRoles());
            $session->set(sprintf('_security_%s', $firewall), serialize($token));
            $session->save();

            $cookie = new Cookie($session->getName(), $session->getId());
            $this->client->getCookieJar()->set($cookie);
        }
    }

    public function logout()
    {
        $this->client->request(Request::METHOD_GET, '\logout');
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        if ($this->user) {
            $user = $this
                ->em
                ->getRepository(User::class)
                ->findOneBy([
                    'email' => $this->user->getEmail(),
                ]);
            $this
                ->em
                ->remove($user);

            $this->em->flush();
        }

        $this->client = null;
        $this->em = null;
        $this->user = null;

        parent::tearDown();
    }
}
