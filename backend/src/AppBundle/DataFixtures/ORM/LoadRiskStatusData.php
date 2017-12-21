<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\RiskStatus;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Insert database entries for Status entity.
 */
class LoadRiskStatusData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 2; ++$i) {
            $riskStatus = (new RiskStatus())
                ->setName('risk-status'.$i)
            ;
            $this->setReference('risk-status'.$i, $riskStatus);
            $manager->persist($riskStatus);
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
