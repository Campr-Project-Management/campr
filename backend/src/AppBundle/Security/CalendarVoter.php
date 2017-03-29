<?php

namespace AppBundle\Security;

use AppBundle\Entity\Calendar;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class CalendarVoter.
 *
 * Restricts users access to Calendar entities
 */
class CalendarVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';
    const DELETE = 'delete';

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * CalendarVoter constructor.
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
        if (!in_array($attribute, [self::VIEW, self::EDIT, self::DELETE])) {
            return false;
        }

        return $subject instanceof Calendar;
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
     * @param Calendar $calendar
     * @param User     $user
     *
     * @return bool
     */
    private function canView(Calendar $calendar, User $user)
    {
        $projectUser = $this
            ->em
            ->getRepository(ProjectUser::class)
            ->findOneBy(['user' => $user, 'project' => $calendar->getProject()])
        ;

        return $projectUser !== null;
    }

    /**
     * Edit access restriction.
     *
     * @param Calendar $calendar
     * @param User     $user
     *
     * @return bool
     */
    private function canEdit(Calendar $calendar, User $user)
    {
        return $this->canView($calendar, $user);
    }

    /**
     * Delete access restriction.
     *
     * @param Calendar $calendar
     * @param User     $user
     *
     * @return bool
     */
    private function canDelete(Calendar $calendar, User $user)
    {
        $projectUser = $this
            ->em
            ->getRepository(ProjectUser::class)
            ->findOneBy(['user' => $user, 'project' => $calendar->getProject()])
        ;

        return $projectUser
            && $projectUser->getProjectRole()
            && $projectUser->getProjectRoleName() === ProjectRole::ROLE_MANAGER
        ;
    }
}
