<?php

namespace AppBundle\Services;

use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectUser;
use AppBundle\Entity\Rasci;
use AppBundle\Entity\User;
use AppBundle\Entity\WorkPackage;
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

        $workPackages = $this
            ->entityManager
            ->getRepository(WorkPackage::class)
            ->findBy([
                'project' => $project->getId(),
            ])
        ;

        $rascis = $this
            ->entityManager
            ->getRepository(Rasci::class)
            ->findByProject($project)
        ;

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
                if ($rasci) {
                    $rasci = reset($rasci);
                    $row['rasci'][] = [
                        'id' => $rasci->getId(),
                        'user' => $user->getId(),
                        'userFullName' => $user->getFullName(),
                        'data' => $rasci->getData(),
                    ];
                } else {
                    $row['rasci'][] = [
                        'id' => null,
                        'user' => $user->getId(),
                        'userFullName' => $user->getFullName(),
                        'data' => null,
                    ];
                }
            }
            $out[] = $row;
        }

        $phases = array_filter($out, function (array $row) {
            return $row['type'] === WorkPackage::TYPE_PHASE;
        });
        $workPackages = [];

        foreach ($phases as $phase) {
            $workPackages[] = $phase;
            $workPackages = array_merge(
                $workPackages,
                array_filter(
                    $out,
                    function (array $row) use ($phase) {
                        return $row['phase'] === $phase['id'];
                    }
                )
            );
        }

        return [
            'workPackages' => $workPackages,
            'users' => $users->getValues(),
        ];
    }
}
