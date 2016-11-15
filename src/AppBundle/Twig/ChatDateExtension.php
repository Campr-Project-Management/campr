<?php

namespace AppBundle\Twig;

class ChatDateExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('chat_date', [$this, 'formatChatDate']),
        );
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
        return 'app_chat_date_extension';
    }
}
