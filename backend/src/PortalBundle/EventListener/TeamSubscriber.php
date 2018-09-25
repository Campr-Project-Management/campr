<?php

namespace PortalBundle\EventListener;

use AppBundle\Command\RedisQueueManagerCommand;
use AppBundle\Entity\Team;
use Component\Team\TeamEvents;
use Predis\Client;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class TeamSubscriber implements EventSubscriberInterface
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
     * TeamSubscriber constructor.
     *
     * @param                 $env
     * @param Client          $redis
     * @param LoggerInterface $logger
     */
    public function __construct($env, Client $redis, LoggerInterface $logger)
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
            TeamEvents::POST_CREATE => 'onTeamPostCreate',
            TeamEvents::POST_RESTORE => 'onTeamPostRestore',
        ];
    }

    /**
     * @param GenericEvent $event
     */
    public function onTeamPostCreate(GenericEvent $event)
    {
        /** @var Team $team */
        $team = $event->getSubject();
        if (!($team instanceof Team)) {
            return;
        }

        $teamEnv = sprintf('%s_%s', str_replace('-', '_', $team->getSlug()), $this->env);

        $this->logger->info('Sending commands to create new team', ['slug' => $team->getSlug()]);

        $this->redis->rpush(
            RedisQueueManagerCommand::DEFAULT,
            [
                sprintf('--env=%s doctrine:database:create -n', $teamEnv),
            ]
        );
        $this->redis->rpush(
            RedisQueueManagerCommand::DEFAULT,
            [
                sprintf('--env=%s doctrine:migrations:migrate -n', $teamEnv),
            ]
        );
        $this->redis->rpush(
            RedisQueueManagerCommand::DEFAULT,
            [
                sprintf(
                    '--env=%s doctrine:fixtures:load --append --fixtures=backend/src/AppBundle/DataFixtures/Team --no-interaction',
                    $teamEnv
                ),
            ]
        );
        $this->redis->rpush(
            RedisQueueManagerCommand::DEFAULT,
            [
                sprintf(
                    '--env=%s app:file-system:create %s',
                    $teamEnv,
                    $team->getSlug()
                ),
            ]
        );
        $this->redis->rpush(
            RedisQueueManagerCommand::DEFAULT,
            [
                sprintf(
                    '--env=%s fos:js-routing:dump --target=web/static/js/fos_js_routes_%s.js',
                    $teamEnv,
                    $team->getSlug()
                ),
            ]
        );
        $this->redis->rpush(
            RedisQueueManagerCommand::DEFAULT,
            [
                sprintf(
                    '--env=%s app:unlock-team %s',
                    $this->env,
                    $team->getSlug()
                ),
            ]
        );
        $this->redis->rpush(
            RedisQueueManagerCommand::DEFAULT,
            [
                sprintf(
                    '--env=%s portal:webhook:workspace:created %s',
                    $this->env,
                    $team->getUuid()
                ),
            ]
        );

        $this->logger->info('Commands to create new team successfully sent', ['slug' => $team->getSlug()]);
    }

    /**
     * @param GenericEvent $event
     */
    public function onTeamPostRestore(GenericEvent $event)
    {
        /** @var Team $team */
        $team = $event->getSubject();
        if (!($team instanceof Team)) {
            return;
        }

        $this->logger->info('Sending command to restore team', ['slug' => $team->getSlug()]);

        $this->redis->rpush(
            RedisQueueManagerCommand::DEFAULT,
            [
                sprintf(
                    '--env=%s portal:webhook:workspace:created %s',
                    $this->env,
                    $team->getUuid()
                ),
            ]
        );

        $this->logger->info('Commands to restore team successfully sent', ['slug' => $team->getSlug()]);
    }
}
