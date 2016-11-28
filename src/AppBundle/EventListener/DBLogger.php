<?php

namespace AppBundle\EventListener;

use Doctrine\Common\Persistence\Mapping\MappingException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Doctrine\ORM\Event\OnFlushEventArgs;
use AppBundle\Entity\Log;
use AppBundle\Entity\User;

class DBLogger
{
    private $tokenStorage;

    public function __construct(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function onFlush(OnFlushEventArgs $event)
    {
        $em = $event->getEntityManager();
        $uok = $em->getUnitOfWork();
        $logMetadata = $em->getClassMetadata(Log::class);

        foreach ($uok->getScheduledEntityUpdates() as $entity) {
            if ($entity instanceof Log) {
                continue;
            }

            $changeSet = $uok->getEntityChangeSet($entity);

            $log = new Log();
            $log->setObjId($entity->getId());
            $log->setClass(get_class($entity));
            $oldValues = [];
            $newValues = [];

            foreach ($changeSet as $key => $value) {
                $oldValues[$key] = $value[0];
                $newValues[$key] = $value[1];
            }

            $log->setOldValue($this->normalizeValue($em, $oldValues));
            $log->setNewValue($this->normalizeValue($em, $newValues));
            if ($token = $this->tokenStorage->getToken()) {
                $user = $token->getUser();

                if ($user instanceof User) {
                    $user = $em
                        ->getRepository(User::class)
                        ->find($user->getId())
                    ;
                    $log->setUser($user);
                }
            }

            $uok->persist($log);
            $uok->computeChangeSet($logMetadata, $log);
        }
    }

    private function normalizeValue(EntityManager $em, $value)
    {
        foreach ($value as $key => $field) {
            if (is_object($field) && !($field instanceof \DateTime)) {
                try {
                    $class = get_class($field);
                    $md = $em->getClassMetadata($class);

                    $value[$key] = [
                        $md->getName(),
                        $field->getId(),
                    ];
                } catch (MappingException $e) {
                }
            }
        }

        return $value;
    }
}
