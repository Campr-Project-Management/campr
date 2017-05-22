<?php

namespace AppBundle\Security;

use AppBundle\Entity\ProjectRole;
use AppBundle\Entity\ProjectUser;
use AppBundle\Entity\WorkPackage;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class WorkPackageVoter.
 *
 * Restricts users access to WorkPackage entities
 */
class WorkPackageVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';
    const DELETE = 'delete';

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * WorkPackageVoter constructor.
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

        return $subject instanceof WorkPackage;
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
     * @param WorkPackage $wp
     * @param User        $user
     *
     * @return bool
     */
    private function canView(WorkPackage $wp, User $user)
    {
        $projectUser = $this
            ->em
            ->getRepository(ProjectUser::class)
            ->findOneBy(['user' => $user, 'project' => $wp->getProject()])
        ;

        return $projectUser || $user === $wp->getResponsibility();
    }

    /**
     * Edit access restriction.
     *
     * @param WorkPackage $wp
     * @param User        $user
     *
     * @return bool
     */
    private function canEdit(WorkPackage $wp, User $user)
    {
        return $this->canView($wp, $user);
    }

    /**
     * Delete access restriction.
     *
     * @param WorkPackage $wp
     * @param User        $user
     *
     * @return bool
     */
    private function canDelete(WorkPackage $wp, User $user)
    {
        $projectUser = $this
            ->em
            ->getRepository(ProjectUser::class)
            ->findOneBy(['user' => $user, 'project' => $wp->getProject()])
        ;

        return ($projectUser
            && $projectUser->hasProjectRole(ProjectRole::ROLE_MANAGER))
            || $user === $wp->getResponsibility()
        ;
    }
}
