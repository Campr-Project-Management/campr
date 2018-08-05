<?php

namespace AppBundle\Services;

use AppBundle\Entity\Rasci;
use AppBundle\Entity\User;
use AppBundle\Entity\WorkPackage;
use Component\Repository\RepositoryInterface;

class RasciWorkPackageSync
{
    /**
     * @var RepositoryInterface
     */
    private $workPackageRepository;

    /**
     * @var RepositoryInterface
     */
    private $rasciRepository;

    /**
     * RasciWorkPackageSync constructor.
     *
     * @param RepositoryInterface $workPackageRepository
     * @param RepositoryInterface $rasciRepository
     */
    public function __construct(RepositoryInterface $workPackageRepository, RepositoryInterface $rasciRepository)
    {
        $this->workPackageRepository = $workPackageRepository;
        $this->rasciRepository = $rasciRepository;
    }

    /**
     * @param Rasci $rasci
     *
     * @return bool
     */
    public function sync(Rasci $rasci): bool
    {
        $this->removeAnyUserAssignmentsToWorkPackage($rasci->getWorkPackage(), $rasci->getUser());
        $updated = $this->syncResponsible($rasci);
        $updated = $this->syncAccountable($rasci) || $updated;
        $updated = $this->syncSupport($rasci) || $updated;
        $updated = $this->syncConsulted($rasci) || $updated;
        $updated = $this->syncInformed($rasci) || $updated;

        return $updated;
    }

    /**
     * @param Rasci $rasci
     */
    public function syncRemoveRasci(Rasci $rasci)
    {
        $this->removeUserAssignmentToWorkPackage($rasci);
    }

    /**
     * @param Rasci $rasci
     *
     * @return bool
     */
    private function syncResponsible(Rasci $rasci): bool
    {
        if (!$rasci->isResponsible()) {
            return false;
        }

        $wp = $rasci->getWorkPackage();
        $user = $rasci->getUser();

        $this->ensureSingleRasciResponsable($wp, $user);
        $wp->setResponsibility($rasci->getUser());

        $this->workPackageRepository->add($wp);

        return true;
    }

    /**
     * @param Rasci $rasci
     *
     * @return bool
     */
    private function syncAccountable(Rasci $rasci): bool
    {
        if (!$rasci->isAccountable()) {
            return false;
        }

        $wp = $rasci->getWorkPackage();
        $user = $rasci->getUser();

        $this->ensureSingleRasciAccountable($wp, $user);
        $wp->setAccountability($rasci->getUser());

        $this->workPackageRepository->add($wp);

        return true;
    }

    /**
     * @param Rasci $rasci
     *
     * @return bool
     */
    private function syncSupport(Rasci $rasci): bool
    {
        if (!$rasci->isSupport()) {
            return false;
        }

        $wp = $rasci->getWorkPackage();
        $wp->addSupportUser($rasci->getUser());

        $this->workPackageRepository->add($wp);

        return true;
    }

    /**
     * @param Rasci $rasci
     *
     * @return bool
     */
    private function syncConsulted(Rasci $rasci): bool
    {
        if (!$rasci->isConsulted()) {
            return false;
        }

        $wp = $rasci->getWorkPackage();
        $wp->addConsultedUser($rasci->getUser());

        $this->workPackageRepository->add($wp);

        return true;
    }

    /**
     * @param Rasci $rasci
     *
     * @return bool
     */
    private function syncInformed(Rasci $rasci): bool
    {
        if (!$rasci->isInformed()) {
            return false;
        }

        $wp = $rasci->getWorkPackage();
        $wp->addInformedUser($rasci->getUser());

        $this->workPackageRepository->add($wp);

        return true;
    }

    /**
     * @param Rasci $rasci
     * @param User  $user
     */
    private function removeUserAssignmentToWorkPackage(Rasci $rasci)
    {
        $wp = $rasci->getWorkPackage();
        $user = $rasci->getUser();

        if ($rasci->isAccountable()) {
            if ($wp->getAccountability() === $user) {
                $wp->setAccountability(null);
            }
        }

        if ($rasci->isResponsible()) {
            if ($wp->getResponsibility() === $user) {
                $wp->setResponsibility(null);
            }
        }

        if ($rasci->isSupport()) {
            $wp->removeSupportUser($user);
        }
        if ($rasci->isConsulted()) {
            $wp->removeConsultedUser($user);
        }
        if ($rasci->isInformed()) {
            $wp->removeInformedUser($user);
        }
        $this->workPackageRepository->add($wp);
    }

    /**
     * @param WorkPackage $wp
     * @param User        $user
     */
    private function removeAnyUserAssignmentsToWorkPackage(WorkPackage $wp, User $user)
    {
        if ($wp->getResponsibility() === $user) {
            $wp->setResponsibility(null);
        }

        if ($wp->getAccountability() === $user) {
            $wp->setAccountability(null);
        }

        $wp->removeSupportUser($user);
        $wp->removeConsultedUser($user);
        $wp->removeInformedUser($user);
    }

    /**
     * @param WorkPackage $wp
     * @param User        $user
     */
    private function ensureSingleRasciResponsable(WorkPackage $wp, User $user)
    {
        /** @var Rasci[] $rascis */
        $rascis = $this->rasciRepository->findBy(
            [
                'workPackage' => $wp->getId(),
                'data' => Rasci::DATA_RESPONSIBLE,
            ]
        );

        foreach ($rascis as $rasci) {
            if ($rasci->getUser() === $user) {
                continue;
            }

            $this->rasciRepository->remove($rasci);
        }
    }

    /**
     * @param WorkPackage $wp
     * @param User        $user
     */
    private function ensureSingleRasciAccountable(WorkPackage $wp, User $user)
    {
        /** @var Rasci[] $rascis */
        $rascis = $this->rasciRepository->findBy(
            [
                'workPackage' => $wp->getId(),
                'data' => Rasci::DATA_ACCOUNTABLE,
            ]
        );

        foreach ($rascis as $rasci) {
            if ($rasci->getUser() === $user) {
                continue;
            }

            $this->rasciRepository->remove($rasci);
        }
    }
}
