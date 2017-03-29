<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\PaymentMethod;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Insert database entries for PaymentMethod entity.
 */
class LoadPaymentMethodData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $paymentMethod = (new PaymentMethod())
            ->setName('method_one')
            ->setToken('token_123')
        ;
        $this->setReference('method_one', $paymentMethod);
        $manager->persist($paymentMethod);
        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 1;
    }
}
