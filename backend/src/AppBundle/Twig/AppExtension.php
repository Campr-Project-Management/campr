<?php

namespace AppBundle\Twig;

class AppExtension extends \Twig_Extension implements \Twig_Extension_InitRuntimeInterface
{
    /**
     * @var bool
     */
    private $showTrackingCode;

    /**
     * @var \Twig_Environment
     */
    public $twig;

    public function __construct($showTrackingCode)
    {
        $this->showTrackingCode = $showTrackingCode;
    }

    /**
     * {@inheritdoc}
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->twig = $environment;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('piwik_tracking', [$this, 'piwikTracking'], ['is_safe' => ['html' => true]]),
        ];
    }

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

    public function piwikTracking()
    {
        return $this->twig->render(':tracking:piwik.html.twig');
    }

    public function getName()
    {
        return 'app_extension';
    }
}
