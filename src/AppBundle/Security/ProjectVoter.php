<?php

namespace AppBundle\Security;

use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectRole;
use AppBundle\Entity\ProjectUser;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class ProjectVoter.
 *
 * Restricts users access to Project entities
 */
class ProjectVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';
    const DELETE = 'delete';

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * ProjectVoter constructor.
     *
     * @param AccessDecisionManagerInterface $decisionManager
     * @param EntityManager                  $em
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

        if (!$subject instanceof Project) {
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

        $project = $subject;
        switch ($attribute) {
            case self::VIEW:
                return $this->canView($project, $user);
            case self::EDIT:
                return $this->canEdit($project, $user);
            case self::DELETE:
                return $this->canDelete($project, $user);
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
        $projectUser = $this
            ->em
            ->getRepository(ProjectUser::class)
            ->findOneBy(['user' => $user, 'project' => $project])
        ;

        return $projectUser !== null;
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
        $projectUser = $this
            ->em
            ->getRepository(ProjectUser::class)
            ->findOneBy(['user' => $user, 'project' => $project])
        ;

        return $projectUser
            && $projectUser->getProjectRole()
            && $projectUser->getProjectRoleName() !== ProjectRole::ROLE_SPONSOR
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
        $projectUser = $this
            ->em
            ->getRepository(ProjectUser::class)
            ->findOneBy(['user' => $user, 'project' => $project])
        ;

        return $projectUser
            && $projectUser->getProjectRole()
            && $projectUser->getProjectRoleName() === ProjectRole::ROLE_MANAGER
        ;
    }
}
