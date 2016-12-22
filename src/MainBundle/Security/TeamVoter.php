<?php

namespace MainBundle\Security;

use AppBundle\Entity\Team;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class TeamVoter.
 *
 * Restricts users access to Team entities
 */
class TeamVoter extends Voter
{
    const EDIT = 'edit';
    const DELETE = 'delete';
    const INVITE = 'invite';

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * ProjectVoter constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

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
        if (!in_array($attribute, [self::EDIT, self::DELETE, self::INVITE])) {
            return false;
        }

        if (!($subject instanceof Team)) {
            return false;
        }

        return true;
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

        $team = $subject;
        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($team, $user);
            case self::DELETE:
                return $this->canDelete($team, $user);
            case self::INVITE:
                return $this->canInvite($team, $user);
        }

        return false;
    }

    /**
     * Edit access restriction.
     *
     * @param Team $team
     * @param User $user
     *
     * @return bool
     */
    private function canEdit(Team $team, User $user)
    {
        $members = $team->getTeamMembers();

        foreach ($members as $member) {
            $userMember = $member->getUser() === $user ? $member : null;
        }

        return $team->getUser() === $user || ($userMember && $userMember->hasRole(User::ROLE_SUPER_ADMIN));
    }

    /**
     * Delete access restriction.
     *
     * @param Team $team
     * @param User $user
     *
     * @return bool
     */
    private function canDelete(Team $team, User $user)
    {
        return $team->getUser() === $user;
    }

    /**
     * Invite access restriction.
     *
     * @param Team $team
     * @param User $user
     *
     * @return bool
     */
    private function canInvite(Team $team, User $user)
    {
        return $this->canEdit($team, $user);
    }
}
