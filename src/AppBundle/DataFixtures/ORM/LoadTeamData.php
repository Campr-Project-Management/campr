<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Team;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Insert database entries for PaymentMethod entity.
 */
class LoadTeamData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $user = $this->getReference('user3');
        for ($i = 1; $i <= 3; ++$i) {
            $team = (new Team())
                ->setName('team_'.$i)
                ->setSlug('team-'.$i)
                ->setUser($user)
                ->setCreatedAt(new \DateTime('2017-01-01'))
            ;
            $this->setReference('team'.$i, $team);
            $manager->persist($team);
        }

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 2;
    }
}
