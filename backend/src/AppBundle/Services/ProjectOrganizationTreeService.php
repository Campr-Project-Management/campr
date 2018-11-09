<?php

namespace AppBundle\Services;

use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectDepartment;
use AppBundle\Entity\Subteam;
use AppBundle\Entity\SubteamMember;
use AppBundle\Entity\User;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Translation\TranslatorInterface;

class ProjectOrganizationTreeService
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var NormalizerInterface
     */
    private $jsonNormalizer;

    /**
     * ProjectOrganizationTreeService constructor.
     *
     * @param TranslatorInterface $translator
     * @param NormalizerInterface $jsonNormalizer
     */
    public function __construct(TranslatorInterface $translator, NormalizerInterface $jsonNormalizer)
    {
        $this->translator = $translator;
        $this->jsonNormalizer = $jsonNormalizer;
    }

    public function buildTree(Project $project)
    {
        return $this->getSponsorData($project);
    }

    private function getSponsorData(Project $project)
    {
        if (!count($project->getProjectSponsors())) {
            return [];
        }

        return $this->extractUserData(
            current($project->getProjectSponsors()),
            [
                'titles' => [
                    $this->translator->trans('roles.project_sponsor', [], 'messages'),
                ],
                'children' => $this->getManagerData($project),
            ]
        );
    }

    private function getManagerData(Project $project)
    {
        if (!count($project->getProjectManagers())) {
            return [];
        }

        $users = [];
        foreach ($project->getProjectManagers() as $projectSponsor) {
            $users[] = $this->extractUserData(
                $projectSponsor,
                [
                    'titles' => [
                        $this->translator->trans('roles.project_manager', [], 'messages'),
                    ],
                    'children' => [],
                ]
            );
        }

        return [
            [
                'id' => 'sponsors',
                'users' => $users,
                'children' => $this->getDepartmentData($project),
            ],
        ];
    }

    private function getDepartmentData(Project $project)
    {
        return $project
            ->getProjectDepartments()
            ->filter(
                function (ProjectDepartment $projectDepartment) {
                    return $projectDepartment->getProjectUsers()->count() > 0;
                }
            )
            ->map(
                function (ProjectDepartment $projectDepartment) {
                    return $this->extractUserData(
                        $projectDepartment->getProjectUsers()->first()->getUser(),
                        [
                            'titles' => [
                                $projectDepartment->getName(),
                            ],
                            'children' => $this->getSubteamData($projectDepartment),
                        ]
                    );
                }
            )
            ->getValues()
            ;
    }

    private function getSubteamData(ProjectDepartment $projectDepartment)
    {
        return $projectDepartment
            ->getSubteams()
            ->filter(
                function (Subteam $subteam) {
                    return $subteam->getSubteamMembers()->count() > 0;
                }
            )
            ->map(
                function (Subteam $subteam) {
                    $manager = $subteam->getSubteamMembers()
                        ->filter(
                            function (SubteamMember $subteamMember) {
                                return $subteamMember->getIsLead();
                            }
                        )
                        ->first()
                    ;

                    if (!$manager) {
                        $manager = $subteam->getSubteamMembers()->first();
                    }

                    if (!$manager) {
                        return [];
                    }

                    return $this->extractUserData(
                        $manager->getUser(),
                        [
                            'titles' => [
                                $this->translator->trans('roles.team_leader', [], 'messages'),
                                $subteam->getName(),
                            ],
                            'children' => $subteam
                                ->getSubteamMembers()
                                ->filter(
                                    function (SubteamMember $subteamMember) use ($manager) {
                                        return $subteamMember->getUser() && $subteamMember->getUser(
                                            ) !== $manager->getUser();
                                    }
                                )
                                ->map(
                                    function (SubteamMember $subteamMember) {
                                        return $this->extractUserData(
                                            $subteamMember->getUser(),
                                            [
                                                'titles' => [
                                                    $this->translator->trans('roles.team_member', [], 'messages'),
                                                ],
                                            ]
                                        );
                                    }
                                )
                                ->getValues(),
                        ]
                    );
                }
            )
            ->filter(
                function (array $data) {
                    return count($data) > 0;
                }
            )
            ->getValues()
        ;
    }

    /**
     * @param User  $user
     * @param array $extraData
     *
     * @return array
     */
    private function extractUserData(User $user, array $extraData = [])
    {
        $data = $this->jsonNormalizer->normalize($user);

        return array_merge(
            $data,
            [
                'titles' => [
                    $this->translator->trans('roles.team_member', [], 'messages'),
                ],
            ],
            $extraData
        );
    }
}
