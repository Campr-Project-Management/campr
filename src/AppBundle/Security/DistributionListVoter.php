<?php

namespace AppBundle\Security;

use AppBundle\Entity\DistributionList;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class DistributionListVoter.
 *
 * Restricts users access to DistributionList entities
 */
class DistributionListVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';
    const DELETE = 'delete';

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
        if (!in_array($attribute, [self::VIEW, self::EDIT, self::DELETE])) {
            return false;
        }

        return $subject instanceof DistributionList;
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

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($subject, $user);
            case self::EDIT:
                return $this->canEdit($subject, $user);
            case self::DELETE:
                return $this->canDelete($subject, $user);
        }

        return false;
    }

    /**
     * View access restriction.
     *
     * @param DistributionList $distributionList
     * @param User             $user
     *
     * @return bool
     */
    private function canView(DistributionList $distributionList, User $user)
    {
        $users = $distributionList->getUsers();

        return $users->contains($user) || $distributionList->getCreatedBy() === $user;
    }

    /**
     * Edit access restriction.
     *
     * @param DistributionList $distributionList
     * @param User             $user
     *
     * @return bool
     */
    private function canEdit(DistributionList $distributionList, User $user)
    {
        return $distributionList->getCreatedBy() === $user;
    }

    /**
     * Delete access restriction.
     *
     * @param DistributionList $distributionList
     * @param User             $user
     *
     * @return bool
     */
    private function canDelete(DistributionList $distributionList, User $user)
    {
        return $this->canEdit($distributionList, $user);
    }
}
