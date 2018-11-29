<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Contract;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Insert database entries for Contract entity.
 */
class LoadContractData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $project = $this->getReference('project1');
        $user = $this->getReference('superadmin');
        $proposedStartDate = new \DateTime('2017-01-01');
        $proposedEndDate = new \DateTime('2017-05-01');

        $contract = new Contract();
        $contract->setName('contract1');
        $contract->setDescription('contract-description1');
        $contract->setCreatedBy($user);
        $contract->setProject($project);
        $contract->setProposedStartDate($proposedStartDate);
        $contract->setProposedEndDate($proposedEndDate);
        $contract->setCreatedAt(new \DateTime('2017-01-01 12:00:00'));
        $this->setReference('contract1', $contract);
        $manager->persist($contract);

        $proposedStartDate = new \DateTime('2017-05-01');
        $proposedEndDate = new \DateTime('2017-08-01');

        $contract = new Contract();
        $contract->setName('contract2');
        $contract->setDescription('contract-description2');
        $contract->setCreatedBy($user);
        $contract->setProject($project);
        $contract->setProposedStartDate($proposedStartDate);
        $contract->setProposedEndDate($proposedEndDate);
        $contract->setCreatedAt(new \DateTime('2017-01-01 12:00:00'));
        $this->setReference('contract2', $contract);
        $manager->persist($contract);

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 3;
    }
}
