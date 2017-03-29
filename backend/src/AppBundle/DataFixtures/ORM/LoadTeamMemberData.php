<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\TeamMember;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Insert database entries for PaymentMethod entity.
 */
class LoadTeamMemberData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $user = $this->getReference('user3');
        $team = $this->getReference('team1');

        for ($i = 1; $i <= 3; ++$i) {
            $teamMember = (new TeamMember())
                ->setUser($user)
                ->setTeam($team)
                ->setRoles(['ROLE_SUPER_ADMIN'])
            ;
            $manager->persist($teamMember);
        }

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
