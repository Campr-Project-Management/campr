<?php

namespace AppBundle\Twig;

class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('chat_date', [$this, 'formatChatDate']),
        ];
    }

    public function getTests()
    {
        return [
            new \Twig_SimpleTest('object', 'is_object'),
        ];
    }

    public function formatChatDate(\DateTime $date)
    {
        $dayStart = new \DateTime();
        $dayStart->setTime(0, 0, 0);
        $dayEnd = new \DateTime();
        $dayEnd->setTime(23, 59, 59);

        return $date > $dayStart && $date < $dayEnd
            ? $date->format('H:i')
            : $date->format('d/m/Y H:i')
        ;
    }

    public function getName()
    {
        return 'app_extension';
    }
}
