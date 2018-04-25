<?php

namespace AppBundle\Services;

use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectUser;
use AppBundle\Entity\Rasci;
use AppBundle\Entity\User;
use AppBundle\Entity\WorkPackage;
use AppBundle\Repository\RasciRepository;
use AppBundle\Repository\WorkPackageRepository;
use Doctrine\ORM\EntityManager;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;

class RasciMatrixService
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * @param EntityManager $entityManager
     * @param Serializer    $serializer
     */
    public function __construct(
        EntityManager $entityManager,
        Serializer $serializer
    ) {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    /**
     * @param Project $project
     *
     * @return array
     */
    public function getDataForProject(Project $project)
    {
        $users = $project
            ->getProjectUsers()
            ->filter(function (ProjectUser $projectUser) {
                return $projectUser->getShowInRasci();
            })
            ->map(function (ProjectUser $projectUser) {
                return $projectUser->getUser();
            })
        ;

        /** @var WorkPackageRepository $workPackageRepo */
        $workPackageRepo = $this
            ->entityManager
            ->getRepository(WorkPackage::class)
        ;

        /** @var WorkPackage[] $workPackages */
        $workPackages = $workPackageRepo->getRasciList($project);

        /** @var RasciRepository $rasciRepo */
        $rasciRepo = $this
            ->entityManager
            ->getRepository(Rasci::class)
        ;

        $rascis = $rasciRepo->findByProject($project);
        $out = [];

        foreach ($workPackages as $workPackage) {
            $row = $this
                ->serializer
                ->toArray(
                    $workPackage,
                    (new SerializationContext())->setSerializeNull(true)
                )
            ;
            $row['rasci'] = [];

            foreach ($users as $user) {
                $rasci = array_filter($rascis, function (Rasci $rasci) use ($user, $workPackage) {
                    return $rasci->getUser() === $user && $rasci->getWorkPackage() === $workPackage;
                });

                $data = [
                    'id' => null,
                    'user' => $user->getId(),
                    'userFullName' => $user->getFullName(),
                    'data' => null,
                ];

                if (!empty($rasci)) {
                    $rasci = array_pop($rasci);
                    $data = array_merge(
                        $data,
                        [
                            'id' => $rasci->getId(),
                            'data' => $rasci->getData(),
                        ]
                    );
                }

                $row['rasci'][] = $data;
            }

            $out[$row['id']] = $row;
        }

        $phases = array_filter(
            $out,
            function (array $row) {
                return WorkPackage::TYPE_PHASE === $row['type'];
            }
        );

        $workPackages = [];

        foreach ($phases as $phase) {
            $phaseWorkPackages = array_filter(
                $out,
                function (array $row) use ($phase) {
                    return $row['phase'] === $phase['id'];
                }
            );

            if (empty($phaseWorkPackages)) {
                continue;
            }

            $workPackages[] = $phase;
            $workPackages = array_merge($workPackages, $phaseWorkPackages);

            $workPackageIds = array_merge(array_column($phaseWorkPackages, 'id'), [$phase['id']]);

            $out = array_filter(
                $out,
                function ($row) use ($workPackageIds) {
                    return !in_array($row['id'], $workPackageIds);
                }
            );
        }

        $out = array_filter($out, function ($row) {
            return $row['isTask'];
        });

        $workPackages = array_merge($workPackages, $out);

        return [
            'workPackages' => $workPackages,
            'users' => $users->getValues(),
        ];
    }
}
