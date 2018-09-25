<?php

namespace Component\TeamInvite;

use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectUser;
use AppBundle\Entity\TeamInvite;
use AppBundle\Entity\User;
use Component\Repository\RepositoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TeamInviter implements TeamInviterInterface
{
    /**
     * @var RepositoryInterface
     */
    private $userRepository;

    /**
     * @var RepositoryInterface
     */
    private $projectUserRepository;

    /**
     * @var RepositoryInterface
     */
    private $teamInviteRepository;

    /**
     * @var TeamInviteSenderInterface
     */
    private $teamInviteSender;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * ProjectUserInviter constructor.
     *
     * @param RepositoryInterface       $userRepository
     * @param RepositoryInterface       $projectUserRepository
     * @param RepositoryInterface       $teamInviteRepository
     * @param TeamInviteSenderInterface $teamInviteSender
     * @param TokenStorageInterface     $tokenStorage
     */
    public function __construct(
        RepositoryInterface $userRepository,
        RepositoryInterface $projectUserRepository,
        RepositoryInterface $teamInviteRepository,
        TeamInviteSenderInterface $teamInviteSender,
        TokenStorageInterface $tokenStorage
    ) {
        $this->userRepository = $userRepository;
        $this->projectUserRepository = $projectUserRepository;
        $this->teamInviteRepository = $teamInviteRepository;
        $this->teamInviteSender = $teamInviteSender;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param string  $email
     * @param string  $teamSlug
     * @param Project $project
     *
     * @return TeamInvite
     */
    public function invite(string $email, string $teamSlug, Project $project): TeamInvite
    {
        $user = $this->findUserByEmail($email);
        if (!$user) {
            $user = $this->createUser($email);
        }

        $projectUser = $user->getProjectUser($project);
        if (!$projectUser) {
            $projectUser = new ProjectUser();
            $projectUser->setUser($user);
            $projectUser->setProject($project);
        }

        $this->userRepository->add($user);
        $this->projectUserRepository->add($projectUser);

        $invite = $this->findOrCreateTeamInvite($project, $user);

        $this->teamInviteSender->send($invite, $teamSlug);
        $this->teamInviteRepository->add($invite);

        return $invite;
    }

    /**
     * @param string $email
     *
     * @return User|null
     */
    private function findUserByEmail(string $email)
    {
        /** @var User $user */
        $user = $this->userRepository->findOneBy(['email' => $email]);

        return $user;
    }

    /**
     * @param string $email
     *
     * @return User
     */
    private function createUser(string $email): User
    {
        list($firstName, $lastName) = explode('@', $email, 2);

        $user = new User();
        $user->setEmail($email);
        $user->setActivatedAt(new \DateTime());
        $user->setIsEnabled(true);
        $user->setIsSuspended(false);
        $user->setUsername($email);
        $user->setPlainPassword(microtime(true));
        $user->setFirstName($firstName);
        $user->setLastName('@'.$lastName);
        $user->setRoles([User::ROLE_USER]);

        return $user;
    }

    /**
     * @param Project $project
     * @param User    $user
     *
     * @return TeamInvite
     */
    private function findOrCreateTeamInvite(Project $project, User $user): TeamInvite
    {
        /** @var TeamInvite $invite */
        $invite = $this
            ->teamInviteRepository
            ->findOneBy(
                [
                    'project' => $project,
                    'email' => $user->getEmail(),
                ]
            )
        ;

        if (!$invite) {
            $invite = new TeamInvite();
            $invite->setEmail($user->getEmail());
            $invite->setProject($project);
            $invite->setUser($user);
            $invite->setInviter($this->getUser());
        }

        return $invite;
    }

    /**
     * @return User|null
     */
    private function getUser()
    {
        $token = $this->tokenStorage->getToken();
        if (!$token) {
            return null;
        }

        $user = $token->getUser();
        if (!($user instanceof User)) {
            return null;
        }

        return $user;
    }
}
