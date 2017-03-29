<?php

namespace AppBundle\Security;

use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class AdminVoter.
 *
 * Grant access to all admins
 */
class AdminVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';
    const DELETE = 'delete';
    const INVITE = 'invite';

    /**
     * Define actions on entity.
     *
     * @param string $attribute
     * @param mixed  $subject
     *
     * @return bool
     */
    protected function supports($attribute, $subject)
    {
        $attributes = [self::VIEW, self::EDIT, self::DELETE, self::INVITE];

        return in_array($attribute, $attributes);
    }

    /**
     * Restrict access.
     *
     * @param string         $attribute
     * @param mixed          $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!($user instanceof User)) {
            return false;
        }

        if ($user->hasRole(User::ROLE_ADMIN) || $user->hasRole(User::ROLE_SUPER_ADMIN)) {
            return true;
        }

        return false;
    }
}
