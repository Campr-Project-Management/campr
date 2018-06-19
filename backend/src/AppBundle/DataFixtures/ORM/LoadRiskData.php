<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Risk;
use Component\TimeUnit\TimeUnitAwareInterface;
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

        $project = $this->getReference('project1');

        for ($i = 1; $i <= 2; ++$i) {
            $dueDate = new \DateTime('2017-03-03');
            $strategy = $this->getReference('risk-strategy'.$i);
            $category = $this->getReference('risk-category'.$i);
            $status = $this->getReference('risk-status'.$i);

            $risk = new Risk();
            $risk->setTitle('title'.$i);
            $risk->setCreatedAt(new \DateTime('2017-01-01 12:00:00'));
            $risk->setDescription('description'.$i);
            $risk->setCost(1);
            $risk->setDelay(1);
            $risk->setDelayUnit(TimeUnitAwareInterface::DAYS);
            $risk->setPriority($i);
            $risk->setDueDate($dueDate);
            $risk->setResponsibility($responsible);
            $risk->setImpact($i * 10);
            $risk->setProbability($i * 10);
            $risk->setRiskStrategy($strategy);
            $risk->setRiskCategory($category);
            $risk->setRiskStatus($status);
            $risk->setProject($project);

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
