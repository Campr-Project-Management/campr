<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\MeetingCategory;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadMeetingCategoryData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $meetingCategory = new MeetingCategory();
        $meetingCategory->setName('Default');

        $manager->persist($meetingCategory);
        $manager->flush();

        $this->setReference('meeting-category-default', $meetingCategory);
    }

    public function getOrder()
    {
        return 1;
    }
}
