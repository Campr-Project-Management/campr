<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $superAdmin = new User();
        $superAdmin->setUsername('superadmin');
        $superAdmin->setFirstName('FirstName1');
        $superAdmin->setLastName('LastName1');
        $superAdmin->setEmail('superadmin@trisoft.ro');
        $superAdmin->setPlainPassword('PasswordSuperAdmin');
        $superAdmin->setRoles(['ROLE_SUPER_ADMIN']);
        $superAdmin->setIsEnabled(true);
        $superAdmin->setPassword($this->encodePassword($superAdmin, $superAdmin->getPlainPassword()));
        $this->setReference('superadmin', $superAdmin);
        $manager->persist($superAdmin);

        $admin = new User();
        $admin->setUsername('admin');
        $admin->setFirstName('FirstName2');
        $admin->setLastName('LastName2');
        $admin->setEmail('admin@trisoft.ro');
        $admin->setPlainPassword('PasswordAdmin');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setIsEnabled(true);
        $admin->setPassword($this->encodePassword($admin, $admin->getPlainPassword()));
        $manager->persist($admin);

        for ($i = 3; $i <= 6; ++$i) {
            $user = (new User())
                ->setUsername('user'.$i)
                ->setFirstName('FirstName'.$i)
                ->setLastName('LastName'.$i)
                ->setEmail('user'.$i.'@trisoft.ro')
                ->setPlainPassword('Password'.$i)
                ->setRoles(['ROLE_USER'])
                ->setIsEnabled(true)
            ;
            $user->setPassword($this->encodePassword($user, $user->getPlainPassword()));
            $this->setReference('user'.$i, $user);
            $manager->persist($user);
        }

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 1;
    }

    /**
     * @param User   $user
     * @param string $plainPassword
     *
     * @return string
     */
    private function encodePassword(User $user, $plainPassword)
    {
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);

        return $encoder->encodePassword($plainPassword, $user->getSalt());
    }
}
