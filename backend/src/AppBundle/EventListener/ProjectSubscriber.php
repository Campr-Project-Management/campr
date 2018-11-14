<?php

namespace AppBundle\EventListener;

use AppBundle\Command\RedisQueueManagerCommand;
use AppBundle\Event\ProjectEvent;
use Component\Project\ProjectEvents;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Predis\Client;

class ProjectSubscriber implements EventSubscriberInterface
{
    /**
     * @var string
     */
    private $env;

    /**
     * @var Client
     */
    private $redis;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * ProjectSubscriber constructor.
     *
     * @param string          $env
     * @param Client          $redis
     * @param LoggerInterface $logger
     */
    public function __construct(string $env, Client $redis, LoggerInterface $logger)
    {
        $this->env = $env;
        $this->redis = $redis;
        $this->logger = $logger;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ProjectEvents::ON_CLONE => 'onClone',
        ];
    }

    /**
     * @param ProjectEvent $event
     */
    public function onClone(ProjectEvent $event)
    {
        $this->logger->info(
            'Cloning project',
            [
                'project' => $event->getProject()->getId(),
                'user' => $event->getUser()->getId(),
                'name' => $event->getName(),
                'env' => $this->env,
            ]
        );

        $this->redis->rpush(
            RedisQueueManagerCommand::DEFAULT,
            [
                sprintf(
                    '--env=%s app:clone-project %s %s \'%s\'',
                    $this->env,
                    $event->getProject()->getId(),
                    $event->getUser()->getId(),
                    $event->getName()
                ),
            ]
        );

        $this->logger->info(
            'Cloning project. Redis command pushed',
            [
                'project' => $event->getProject()->getId(),
                'user' => $event->getUser()->getId(),
                'name' => $event->getName(),
                'env' => $this->env,
            ]
        );
    }
}
