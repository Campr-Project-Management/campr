<?php

namespace MainBundle\EventListener;

use AppBundle\Command\RedisQueueManagerCommand;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use TSS\AutomailerBundle\DefaultEntity\Automailer;
use Predis\Client;

/**
 * Class AutomailerListener
 * Creates a queue for automailer spool
 */
class AutomailerListener
{
    private $env;

    private $redis;

    private $subdomain;

    private $automailerFound;

    /**
     * AutomailerListener constructor.
     *
     * @param $env
     * @param Client $redis
     */
    public function __construct($env, $subdomain, Client $redis)
    {
        $this->env = $env;
        $this->redis = $redis;
        $this->subdomain = $subdomain;
    }

    /**
     * @param OnFlushEventArgs $event
     */
    public function onFlush(OnFlushEventArgs $event)
    {
        $em = $event->getEntityManager();
        $uok = $em->getUnitOfWork();

        foreach ($uok->getScheduledEntityInsertions() as $entity) {
            if ($entity instanceof Automailer) {
                $this->automailerFound = true;
                break;
            }
        }
    }

    /**
     * @param PostFlushEventArgs $event
     */
    public function postFlush(PostFlushEventArgs $event)
    {
        if ($this->subdomain && $this->subdomain !== 'team' && $this->automailerFound) {
            $this->redis->rpush(RedisQueueManagerCommand::AUTOMAILER, [
                sprintf(
                    'automailer:spool:send --env=%s',
                    $this->subdomain . '_' . $this->env
                ),
            ]);
        }
    }
}
