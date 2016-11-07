<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\ProjectRole;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadProjectRoleData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $roleManager = (new ProjectRole())
            ->setName('manager')
            ->setSequence(1)
        ;
        $this->setReference('manager', $roleManager);
        $manager->persist($roleManager);

        $sponsor = (new ProjectRole())
            ->setName('sponsor')
            ->setSequence(1)
        ;
        $this->setReference('sponsor', $sponsor);
        $manager->persist($sponsor);

        $teamMember = (new ProjectRole())
            ->setName('team-member')
            ->setSequence(2)
        ;
        $this->setReference('team-member', $teamMember);
        $manager->persist($teamMember);

        $teamParticipant = (new ProjectRole())
            ->setName('team-participant')
            ->setSequence(2)
        ;
        $this->setReference('team-participant', $teamParticipant);
        $manager->persist($teamParticipant);

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
