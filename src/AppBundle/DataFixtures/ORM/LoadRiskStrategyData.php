<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\RiskStrategy;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadRiskStrategyData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 2; ++$i) {
            $riskStrategy = (new RiskStrategy())
                ->setName('risk-strategy'.$i)
                ->setSequence($i)
            ;
            $this->setReference('risk-strategy'.$i, $riskStrategy);
            $manager->persist($riskStrategy);
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
}
