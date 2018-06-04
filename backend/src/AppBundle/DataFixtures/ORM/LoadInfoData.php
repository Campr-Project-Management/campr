<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Info;
use AppBundle\Entity\InfoCategory;
use AppBundle\Entity\Project;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Insert database entries for Note entity.
 */
class LoadInfoData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        /** @var Project $project */
        $project = $this->getReference('project1');
        $meeting = $this->getReference('meeting1');
        $responsible = $this->getReference('user4');
        $expiresAt = new \DateTime('2017-05-01');

        for ($i = 1; $i <= 2; ++$i) {
            /** @var InfoCategory $category */
            $category = $this->getReference('infoCategory'.$i);

            $info = new Info();
            $info->setTopic('note'.$i);
            $info->setDescription('description'.$i);
            $info->setProject($project);
            $info->setMeeting($meeting);
            $info->setInfoCategory($category);
            $info->setResponsibility($responsible);
            $info->setExpiresAt($expiresAt);

            $manager->persist($info);
        }

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
