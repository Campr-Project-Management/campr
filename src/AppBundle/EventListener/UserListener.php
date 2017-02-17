<?php

namespace AppBundle\EventListener;

use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticator;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Doctrine\ORM\Event\OnFlushEventArgs;
use AppBundle\Services\UserService;
use AppBundle\Entity\User;

/**
 * Class UserListener
 * Encodes password and sets activation token for a new user.
 */
class UserListener
{
    private $userService;
    private $encoder;
    private $googleAuthenticator;

    /**
     * UserListener constructor.
     *
     * @param UserService         $userService
     * @param UserPasswordEncoder $encoder
     */
    public function __construct(UserService $userService, UserPasswordEncoder $encoder, GoogleAuthenticator $googleAuthenticator)
    {
        $this->userService = $userService;
        $this->encoder = $encoder;
        $this->googleAuthenticator = $googleAuthenticator;
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
                $activationToken = $this->userService->generateActivationResetToken();
                $entity->setActivationToken($activationToken);
                $entity->setActivationTokenCreatedAt(new \DateTime());
                $entity->setGoogleAuthenticatorSecret($this->googleAuthenticator->generateSecret());

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
