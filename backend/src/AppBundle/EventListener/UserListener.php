<?php

namespace AppBundle\EventListener;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Doctrine\ORM\Event\OnFlushEventArgs;
use AppBundle\Entity\User;

/**
 * Class UserListener
 * Encodes password and sets activation token for a new user.
 */
class UserListener
{
    private $encoder;

    /**
     * UserListener constructor.
     *
     * @param UserPasswordEncoder $encoder
     */
    public function __construct(UserPasswordEncoder $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @param OnFlushEventArgs $event
     */
    public function onFlush(OnFlushEventArgs $event)
    {
        $em = $event->getEntityManager();
        $uok = $em->getUnitOfWork();

        foreach ($uok->getScheduledEntityInsertions() as $entity) {
            if ($entity instanceof User && $entity->getPlainPassword()) {
                $password = $this
                    ->encoder
                    ->encodePassword($entity, $entity->getPlainPassword())
                ;
                $entity->setPassword($password);
                if (!$entity->getActivationToken() && !$entity->getActivationTokenCreatedAt()) {
                    $activationToken = substr(md5(microtime()), rand(0, 26), 6);
                    $entity->setActivationToken($activationToken);
                    $entity->setActivationTokenCreatedAt(new \DateTime());
                }
                $uok->recomputeSingleEntityChangeSet(
                    $em->getClassMetadata(User::class),
                    $entity
                );
            }
        }

        foreach ($uok->getScheduledEntityUpdates() as $entity) {
            if ($entity instanceof User && $entity->getPlainPassword()) {
                $password = $this
                    ->encoder
                    ->encodePassword($entity, $entity->getPlainPassword())
                ;
                $entity->setPassword($password);
                $uok->recomputeSingleEntityChangeSet(
                    $em->getClassMetadata(User::class),
                    $entity
                );
            }
        }
    }
}
