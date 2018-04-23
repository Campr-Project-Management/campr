<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\MeetingCategory;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadMeetingCategoryData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $category = new MeetingCategory();
        $category->setName('Category1');
        $this->setReference('meetingCategory1', $category);
        $manager->persist($category);

        $category = new MeetingCategory();
        $category->setName('Category2');
        $this->setReference('meetingCategory2', $category);
        $manager->persist($category);

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
