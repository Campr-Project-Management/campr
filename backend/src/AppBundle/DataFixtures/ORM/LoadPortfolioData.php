<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Portfolio;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Insert database entries for Portfolio entity.
 */
class LoadPortfolioData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 2; ++$i) {
            $portfolio = (new Portfolio())
                ->setName('portfolio'.$i)
                ->setCreatedAt(new \DateTime('2017-01-01 12:00:00'))
                ->setDescription('Description for portfolio'.$i)
            ;
            $this->setReference('portfolio'.$i, $portfolio);
            $manager->persist($portfolio);
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
