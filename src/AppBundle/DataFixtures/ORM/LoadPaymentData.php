<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Payment;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Insert database entries for PaymentMethod entity.
 */
class LoadPaymentData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $method = $this->getReference('method_one');
        $team = $this->getReference('team2');

        $payment = (new Payment())
            ->setPaymentMethod($method)
            ->setTeam($team)
        ;
        $manager->persist($payment);
        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 4;
    }
}
