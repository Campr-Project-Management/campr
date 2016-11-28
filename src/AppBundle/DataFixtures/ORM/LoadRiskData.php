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
            $dueDate = new \DateTime(sprintf('+%d days', $i + 1));
            $impact = $this->getReference('impact'.$i);
            $strategy = $this->getReference('risk-strategy'.$i);
            $category = $this->getReference('risk-category'.$i);
            $status = $this->getReference('status'.$i);

            $risk = (new Risk())
                ->setTitle('title'.$i)
                ->setDescription('description'.$i)
                ->setCost('cost'.$i)
                ->setBudget('budget'.$i)
                ->setDelay('delay'.$i)
                ->setPriority('priority'.$i)
                ->setMeasure('measure'.$i)
                ->setDueDate($dueDate)
                ->setResponsibility($responsible)
                ->setImpact($impact)
                ->setRiskStrategy($strategy)
                ->setRiskCategory($category)
                ->setStatus($status)
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
