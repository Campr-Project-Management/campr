<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\RiskCategory;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Insert database entries for RiskCategory entity.
 */
class LoadRiskCategoryData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 2; ++$i) {
            $riskCategory = (new RiskCategory())
                ->setName('risk-category'.$i)
                ->setSequence($i)
            ;
            $this->setReference('risk-category'.$i, $riskCategory);
            $manager->persist($riskCategory);
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
