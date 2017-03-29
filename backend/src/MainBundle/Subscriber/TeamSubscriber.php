<?php

namespace MainBundle\Subscriber;

use AppBundle\Command\RedisQueueManagerCommand;
use Predis\Client;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use MainBundle\Event\TeamEvent;

class TeamSubscriber implements EventSubscriberInterface
{
    private $env;

    private $redis;

    /**
     * TeamSubscriber constructor.
     *
     * @param $env
     * @param Client $redis
     */
    public function __construct($env, Client $redis)
    {
        $this->env = $env;
        $this->redis = $redis;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            TeamEvent::CREATED => 'onTeamCreate',
        ];
    }

    /**
     * @param TeamEvent $event
     */
    public function onTeamCreate(TeamEvent $event)
    {
        $team = $event->getTeam();
        $this->redis->rpush(RedisQueueManagerCommand::DEFAULT, [
            sprintf(
                '--env=%s_%s doctrine:database:create -n',
                str_replace('-', '_', $team->getSlug()),
                $this->env
            ),
        ]);
        $this->redis->rpush(RedisQueueManagerCommand::DEFAULT, [
            sprintf(
                '--env=%s_%s doctrine:migrations:migrate -n',
                str_replace('-', '_', $team->getSlug()),
                $this->env
            ),
        ]);
        $this->redis->rpush(RedisQueueManagerCommand::DEFAULT, [
            sprintf(
                '--env=%s_%s doctrine:fixtures:load -n --fixtures=src/AppBundle/DataFixtures/Team',
                str_replace('-', '_', $team->getSlug()),
                $this->env
            ),
        ]);
        $this->redis->rpush(RedisQueueManagerCommand::DEFAULT, [
            sprintf(
                '--env=%s_%s app:file-system:create %s',
                str_replace('-', '_', $team->getSlug()),
                $this->env,
                $team->getSlug()
            ),
        ]);
        $this->redis->rpush(RedisQueueManagerCommand::DEFAULT, [
            sprintf(
                '--env=%s_%s fos:js-routing:dump --target=web/static/js/fos_js_routes_%s.js',
                str_replace('-', '_', $team->getSlug()),
                $this->env,
                $team->getSlug()
            ),
        ]);
    }
}
