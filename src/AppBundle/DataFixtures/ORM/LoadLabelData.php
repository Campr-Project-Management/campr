<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Label;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Insert database entries for Label entity.
 */
class LoadLabelData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $project = $this->getReference('project1');
        for ($i = 1; $i <= 2; ++$i) {
            $label = new Label();
            $label->setTitle('label-title'.$i);
            $label->setProject($project);
            $label->setColor('color'.$i);

            $manager->persist($label);
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
