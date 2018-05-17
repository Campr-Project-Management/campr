<?php

namespace MainBundle\Tests\Controller;

use AppBundle\Entity\Subteam;
use AppBundle\Entity\SubteamMember;
use AppBundle\Entity\SubteamRole;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class BaseController extends WebTestCase
{
    /** @var Client */
    protected $client;

    /** @var EntityManager */
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

        $domain = $this->client->getContainer()->getParameter('domain');
        $bundle = explode('\\', get_class($this));
        $host = 'AppBundle' === $bundle[0] ? 'team.'.$domain : $domain;

        $this->client->setServerParameters([
            'HTTP_HOST' => $host,
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
        $user = $this->em->getRepository(User::class)->findBy(['username' => $username]);
        if (!$user) {
            $user = new User();
        }

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

    /**
     * @param string $username
     *
     * @return User|null
     */
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

            $firewalls = ['main', 'app'];

            foreach ($firewalls as $firewall) {
                $token = new UsernamePasswordToken($user, null, $firewall, $user->getRoles());
                $session->set(sprintf('_security_%s', $firewall), serialize($token));
            }

            $session->save();

            $cookie = new Cookie($session->getName(), $session->getId());
            $this->client->getCookieJar()->set($cookie);
        }
    }

    public function logout()
    {
        $this->client->request(Request::METHOD_GET, '/logout');
    }

    /**
     * @param string $name
     * @param string $description
     *
     * @return Subteam
     */
    public function createSubteam($name, $description)
    {
        $subteam = new Subteam();
        $subteam
            ->setName($name)
            ->setDescription($description)
        ;
        $this->em->persist($subteam);
        $this->em->flush();

        return $subteam;
    }

    /**
     * @param User    $user
     * @param Subteam $subteam
     *
     * @return SubteamMember
     */
    public function createSubteamMember(User $user, Subteam $subteam)
    {
        $subteamMember = new SubteamMember();
        $subteamMember
            ->setUser($user)
            ->setSubteam($subteam)
        ;
        $this->em->persist($subteamMember);
        $this->em->flush();

        return $subteamMember;
    }

    /**
     * @param string                $name
     * @param string                $description
     * @param array|SubteamMember[] $subteamMembers
     *
     * @return SubteamRole
     */
    public function createSubteamRole($name, $description = '', $subteamMembers = [])
    {
        $subteamRole = new SubteamRole();
        $subteamRole
            ->setName($name)
            ->setDescription($description)
        ;

        foreach ($subteamMembers as $subteamMember) {
            $subteamRole->addSubteamMember($subteamMember);
        }

        $this->em->persist($subteamRole);
        $this->em->flush();

        return $subteamRole;
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        if ($this->user) {
            $this->em->clear();
            $user = $this
                ->em
                ->getRepository(User::class)
                ->findOneBy([
                    'email' => $this->user->getEmail(),
                ]);
            $this
                ->em
                ->remove($user)
            ;

            $this->em->flush();
        }

        $this->client = null;
        $this->em = null;
        $this->user = null;

        parent::tearDown();
    }
}
