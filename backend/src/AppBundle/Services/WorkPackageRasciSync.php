<?php

namespace AppBundle\Services;

use AppBundle\Entity\Enum\ProjectModuleTypeEnum;
use AppBundle\Entity\ProjectUser;
use AppBundle\Entity\Rasci;
use AppBundle\Entity\User;
use AppBundle\Entity\WorkPackage;
use Component\Repository\RepositoryInterface;

class WorkPackageRasciSync
{
    /**
     * @var RepositoryInterface
     */
    private $rasciRepository;

    /**
     * @var RepositoryInterface
     */
    private $projectUserRepository;

    /**
     * WorkPackageRasciSync constructor.
     *
     * @param RepositoryInterface $rasciRepository
     * @param RepositoryInterface $projectUserRepository
     */
    public function __construct(RepositoryInterface $rasciRepository, RepositoryInterface $projectUserRepository)
    {
        $this->rasciRepository = $rasciRepository;
        $this->projectUserRepository = $projectUserRepository;
    }

    /**
     * @param WorkPackage $wp
     *
     * @return bool
     */
    public function sync(WorkPackage $wp): bool
    {
        $project = $wp->getProject();
        if (!$project->hasProjectModule(ProjectModuleTypeEnum::RASCI_MATRIX)) {
            return false;
        }

        $this->removeAnyRasciRole($wp);

        $updated = $this->syncResponsible($wp);
        $updated = $this->syncAccountable($wp) || $updated;
        $updated = $this->syncSupport($wp) || $updated;
        $updated = $this->syncConsulted($wp) || $updated;
        $updated = $this->syncInformed($wp) || $updated;

        return $updated;
    }

    /**
     * @param WorkPackage $wp
     *
     * @return bool
     */
    private function syncResponsible(WorkPackage $wp): bool
    {
        $user = $wp->getResponsibility();
        if (!$user || $this->hasAnyRasciRole($wp, $user)) {
            return false;
        }

        $rasci = new Rasci();
        $rasci->setWorkPackage($wp);
        $rasci->setUser($user);
        $rasci->setData(Rasci::DATA_RESPONSIBLE);

        $this->rasciRepository->add($rasci);

        /** @var ProjectUser $projectUser */
        $projectUser = $user->getProjectUser($wp->getProject());
        $projectUser->setShowInRasci(true);

        $this->projectUserRepository->add($projectUser);

        return true;
    }

    /**
     * @param WorkPackage $wp
     *
     * @return bool
     */
    private function syncAccountable(WorkPackage $wp): bool
    {
        $user = $wp->getAccountability();
        if (!$user || $this->hasAnyRasciRole($wp, $user)) {
            return false;
        }

        $rasci = new Rasci();
        $rasci->setWorkPackage($wp);
        $rasci->setUser($user);
        $rasci->setData(Rasci::DATA_ACCOUNTABLE);

        $this->rasciRepository->add($rasci);

        /** @var ProjectUser $projectUser */
        $projectUser = $user->getProjectUser($wp->getProject());
        $projectUser->setShowInRasci(true);

        $this->projectUserRepository->add($projectUser);

        return true;
    }

    /**
     * @param WorkPackage $wp
     *
     * @return bool
     */
    private function syncSupport(WorkPackage $wp): bool
    {
        $users = $wp->getSupportUsers();
        if (!count($users)) {
            return false;
        }

        foreach ($users as $user) {
            if ($this->hasAnyRasciRole($wp, $user)) {
                continue;
            }

            $rasci = new Rasci();
            $rasci->setWorkPackage($wp);
            $rasci->setUser($user);
            $rasci->setData(Rasci::DATA_SUPPORT);

            $this->rasciRepository->add($rasci);

            /** @var ProjectUser $projectUser */
            $projectUser = $user->getProjectUser($wp->getProject());
            $projectUser->setShowInRasci(true);

            $this->projectUserRepository->add($projectUser);
        }

        return true;
    }

    /**
     * @param WorkPackage $wp
     *
     * @return bool
     */
    private function syncConsulted(WorkPackage $wp): bool
    {
        $users = $wp->getConsultedUsers();
        if (!count($users)) {
            return false;
        }

        foreach ($users as $user) {
            if ($this->hasAnyRasciRole($wp, $user)) {
                continue;
            }

            $rasci = new Rasci();
            $rasci->setWorkPackage($wp);
            $rasci->setUser($user);
            $rasci->setData(Rasci::DATA_CONSULTED);

            $this->rasciRepository->add($rasci);

            /** @var ProjectUser $projectUser */
            $projectUser = $user->getProjectUser($wp->getProject());
            $projectUser->setShowInRasci(true);

            $this->projectUserRepository->add($projectUser);
        }

        return true;
    }

    /**
     * @param WorkPackage $wp
     *
     * @return bool
     */
    private function syncInformed(WorkPackage $wp): bool
    {
        $users = $wp->getInformedUsers();
        if (!count($users)) {
            return false;
        }

        foreach ($users as $user) {
            if ($this->hasAnyRasciRole($wp, $user)) {
                continue;
            }

            $rasci = new Rasci();
            $rasci->setWorkPackage($wp);
            $rasci->setUser($user);
            $rasci->setData(Rasci::DATA_INFORMED);

            $this->rasciRepository->add($rasci);

            /** @var ProjectUser $projectUser */
            $projectUser = $user->getProjectUser($wp->getProject());
            $projectUser->setShowInRasci(true);

            $this->projectUserRepository->add($projectUser);
        }

        return true;
    }

    /**
     * @param WorkPackage $wp
     *
     * @return bool
     */
    private function removeAnyRasciRole(WorkPackage $wp): bool
    {
        $rascis = $this->rasciRepository->findBy(
            [
                'workPackage' => $wp->getId(),
            ]
        );

        foreach ($rascis as $rasci) {
            $this->rasciRepository->remove($rasci);
        }

        return count($rascis) > 0;
    }

    /**
     * @param WorkPackage $wp
     * @param User        $user
     *
     * @return bool
     */
    private function hasAnyRasciRole(WorkPackage $wp, User $user): bool
    {
        return (bool) $this->rasciRepository->findOneBy(
            [
                'workPackage' => $wp->getId(),
                'user' => $user->getId(),
            ]
        );
    }
}
