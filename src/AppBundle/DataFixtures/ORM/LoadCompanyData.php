<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Company;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Insert database entries for Company entity.
 */
class LoadCompanyData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 2; ++$i) {
            $company = (new Company())
                ->setName('company'.$i)
            ;
            $this->setReference('company'.$i, $company);
            $manager->persist($company);
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
