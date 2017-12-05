<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Company;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCompanyData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $company = new Company();
        $company->setName('Default');

        $manager->persist($manager);
        $manager->flush();

        $this->setReference('company-default', $company);
    }

    public function getOrder()
    {
        return 1;
    }
}
