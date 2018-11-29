<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\DistributionList;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Insert database entries for DistributionList entity.
 */
class LoadDistributionListData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $project = $this->getReference('project1');
        $createdBy = $this->getReference('superadmin');
        $user10 = $this->getReference('user10');
        $createdAt = new \DateTime('2017-01-01 07:00:00');

        $distributionList = new DistributionList();
        $distributionList->setName('distribution-list-1');
        $distributionList->setSequence(1);
        $distributionList->setCreatedBy($createdBy);
        $distributionList->setProject($project);
        $distributionList->addUser($user10);
        $distributionList->setCreatedAt($createdAt);
        $manager->persist($distributionList);
        $this->setReference('distribution-list-1', $distributionList);

        $distributionList2 = new DistributionList();
        $distributionList2->setName('distribution-list-2');
        $distributionList2->setSequence(1);
        $distributionList2->setCreatedBy($createdBy);
        $distributionList2->setProject($project);
        $distributionList2->addUser($user10);
        $distributionList2->setCreatedAt($createdAt);
        $manager->persist($distributionList2);
        $this->setReference('distribution-list-2', $distributionList2);

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
