<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Risk;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Insert database entries for Risk entity.
 */
class LoadRiskData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $responsible = $this->getReference('user3');

        for ($i = 1; $i <= 2; ++$i) {
            $dueDate = new \DateTime('2017-03-03');
            $strategy = $this->getReference('risk-strategy'.$i);
            $category = $this->getReference('risk-category'.$i);
            $status = $this->getReference('risk-status'.$i);

            $risk = (new Risk())
                ->setTitle('title'.$i)
                ->setCreatedAt(new \DateTime('2017-01-01 12:00:00'))
                ->setDescription('description'.$i)
                ->setCost(1)
                ->setCurrency('USD')
                ->setBudget(1)
                ->setDelay(1)
                ->setDelayUnit('days')
                ->setPriority('priority'.$i)
                ->setDueDate($dueDate)
                ->setResponsibility($responsible)
                ->setImpact($i * 10)
                ->setProbability($i * 10)
                ->setRiskStrategy($strategy)
                ->setRiskCategory($category)
                ->setRiskStatus($status)
            ;
            $manager->persist($risk);
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
