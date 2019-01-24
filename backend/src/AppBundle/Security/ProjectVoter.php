<?php

namespace AppBundle\Security;

use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectRole;
use AppBundle\Entity\ProjectUser;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class ProjectVoter.
 *
 * Restricts users access to Project entities
 */
class ProjectVoter extends Voter
{
    const CREATE = 'create';
    const VIEW = 'view';
    const EDIT = 'edit';
    const DELETE = 'delete';

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * ProjectVoter constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
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
        if (!in_array($attribute, [self::CREATE, self::VIEW, self::EDIT, self::DELETE])) {
            return false;
        }

        return $subject instanceof Project;
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

        if ($user->isAdmin()) {
            return true;
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
     * @param Project $project
     * @param User    $user
     *
     * @return bool
     */
    private function canView(Project $project, User $user)
    {
        $projectUser = $this->em->getRepository(ProjectUser::class)->findOneBy(['user' => $user, 'project' => $project]);

        return null !== $projectUser;
    }

    /**
     * Edit access restriction.
     *
     * @param Project $project
     * @param User    $user
     *
     * @return bool
     */
    private function canEdit(Project $project, User $user)
    {
        /** @var ProjectUser $projectUser */
        $projectUser = $this->em->getRepository(ProjectUser::class)->findOneBy(['user' => $user, 'project' => $project]);

        return $projectUser
            && $projectUser->hasProjectRole(ProjectRole::ROLE_MANAGER, ProjectRole::ROLE_SPONSOR)
        ;
    }

    /**
     * Delete access restriction.
     *
     * @param Project $project
     * @param User    $user
     *
     * @return bool
     */
    private function canDelete(Project $project, User $user)
    {
        $projectUser = $this->em->getRepository(ProjectUser::class)->findOneBy(['user' => $user, 'project' => $project]);

        return $projectUser
            && $projectUser->hasProjectRole(ProjectRole::ROLE_MANAGER, ProjectRole::ROLE_SPONSOR)
        ;
    }
}
