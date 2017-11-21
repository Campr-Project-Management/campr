<?php

namespace AppBundle\Services;

use AppBundle\Command\RedisQueueManagerCommand;
use Predis\Client;
use Swift_Events_SendEvent;

class AutomailerRedisPlugin implements \Swift_Events_SendListener
{
    /**
     * @var string
     */
    private $environment;

    /**
     * @var Client
     */
    private $redis;

    /**
     * AutomailerRedisPlugin constructor.
     * @param string $environment
     * @param Client $redis
     */
    public function __construct($environment, Client $redis)
    {
        $this->environment = $environment;
        $this->redis = $redis;
    }


    public function beforeSendPerformed(Swift_Events_SendEvent $evt)
    {
    }

    /**
     * Invoked immediately after the Message is sent.
     *
     * @param Swift_Events_SendEvent $evt
     */
    public function sendPerformed(Swift_Events_SendEvent $evt)
    {
        $this->redis->rpush(RedisQueueManagerCommand::AUTOMAILER, [
            sprintf(
                'automailer:spool:send --env=%s',
                $this->environment
            ),
        ]);
    }
}
