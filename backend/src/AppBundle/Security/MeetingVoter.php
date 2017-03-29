<?php

namespace AppBundle\Security;

use AppBundle\Entity\Meeting;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class MeetingVoter.
 *
 * Restricts users access to Meeting entities
 */
class MeetingVoter extends Voter
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

        return $subject instanceof Meeting;
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
     * @param Meeting $meeting
     * @param User    $user
     *
     * @return bool
     */
    private function canView(Meeting $meeting, User $user)
    {
        $participants = $meeting->getMeetingParticipants();
        $isParticipant = false;
        foreach ($participants as $participant) {
            if ($participant->getUser() === $user) {
                $isParticipant = true;
                break;
            }
        }

        return $isParticipant || $meeting->getCreatedBy() === $user;
    }

    /**
     * Edit access restriction.
     *
     * @param Meeting $meeting
     * @param User    $user
     *
     * @return bool
     */
    private function canEdit(Meeting $meeting, User $user)
    {
        return $meeting->getCreatedBy() === $user;
    }

    /**
     * Delete access restriction.
     *
     * @param Meeting $meeting
     * @param User    $user
     *
     * @return bool
     */
    private function canDelete(Meeting $meeting, User $user)
    {
        return $this->canEdit($meeting, $user);
    }
}
