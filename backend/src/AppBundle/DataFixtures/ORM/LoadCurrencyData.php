<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Currency;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCurrencyData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $currency = $manager->getRepository(Currency::class)->findOneBy(['code' => 'EUR']);
        if (!$currency) {
            $currency = new Currency();
            $currency->setCode('EUR');
            $manager->persist($currency);
        }

        $this->setReference('currencyEUR', $currency);

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
