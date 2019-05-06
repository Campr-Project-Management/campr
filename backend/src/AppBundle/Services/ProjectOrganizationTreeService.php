<?php

namespace AppBundle\Services;

use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectDepartment;
use AppBundle\Entity\ProjectDepartmentMember;
use AppBundle\Entity\ProjectUser;
use AppBundle\Entity\Subteam;
use AppBundle\Entity\SubteamMember;
use AppBundle\Entity\User;
use Component\User\Model\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
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

    /**
     * @param Project $project
     *
     * @return array
     */
    public function buildTree(Project $project)
    {
        return $this->getSponsorData($project);
    }

    /**
     * @param Project $project
     *
     * @return array
     */
    private function getSponsorData(Project $project)
    {
        if (!count($project->getProjectSponsors())) {
            return [];
        }

        /** @var ProjectUser $sponsor */
        $sponsor = $project->getProjectSponsors()[0];

        return $this->extractUserData(
            $sponsor->getUser(),
            [
                'titles' => [
                    $this->translator->trans('roles.project_sponsor', [], 'messages'),
                ],
                'children' => $this->getManagerData($project),
            ]
        );
    }

    /**
     * @param Project $project
     *
     * @return array
     */
    private function getManagerData(Project $project)
    {
        if (!count($project->getProjectManagers())) {
            return [];
        }

        $users = [];
        foreach ($project->getProjectManagers() as $projectSponsor) {
            $users[] = $this->extractUserData(
                $projectSponsor->getUser(),
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
                'children' => $this->serializeDepartments($project->getProjectDepartments()),
            ],
        ];
    }

    /**
     * @param ProjectDepartment[]|ArrayCollection $departments
     *
     * @return array
     */
    private function serializeDepartments($departments)
    {
        return $departments
            ->filter(
                function (ProjectDepartment $projectDepartment) {
                    return count($projectDepartment->getMembers()) > 0;
                }
            )
            ->map(
                function (ProjectDepartment $department) {
                    return $this->serializeDepartment($department);
                }
            )
            ->getValues();
    }

    /**
     * @param ProjectDepartment $department
     *
     * @return array
     */
    private function serializeDepartment(ProjectDepartment $department)
    {
        /** @var ProjectDepartmentMember $member */
        $member = $department->getMembers()->first();
        $leader = $department->getLeader() ?? $member->getProjectUser();

        $extraData = [
            'titles' => [
                $department->getName(),
            ],
            'children' => $this->serializeSubteams($department->getSubteams()),
            'members' => $this->serializeDepartmentMembers($department->getMembers()),
        ];

        if ($department->hasLeader()) {
            array_unshift(
                $extraData['titles'],
                $this->translator->trans('roles.team_leader', [], 'messages')
            );
        }

        return $this->extractUserData($leader->getUser(), $extraData);
    }

    /**
     * @param ProjectDepartmentMember[]|ArrayCollection $members
     *
     * @return array
     */
    private function serializeDepartmentMembers($members)
    {
        return $members
            ->filter(
                function (ProjectDepartmentMember $member) {
                    return !$member->isLead();
                }
            )
            ->map(
                function (ProjectDepartmentMember $member) {
                    return $this->extractUserData(
                        $member->getProjectUser()->getUser(),
                        [
                            'titles' => [
                                $this->translator->trans('message.department_member', [], 'messages'),
                            ],
                        ]
                    );
                }
            )
            ->getValues();
    }

    /**
     * @param Subteam[]|ArrayCollection $subteams
     *
     * @return array
     */
    private function serializeSubteams($subteams)
    {
        return $subteams
            ->filter(
                function (Subteam $subteam) {
                    return $subteam->getSubteamMembers()->count() > 0;
                }
            )
            ->map(
                function (Subteam $subteam) {
                    return $this->serializeSubteam($subteam);
                }
            )
            ->filter(
                function (array $data) {
                    return count($data) > 0;
                }
            )
            ->getValues();
    }

    /**
     * @param Subteam $subteam
     *
     * @return array
     */
    private function serializeSubteam(Subteam $subteam)
    {
        /** @var SubteamMember $first */
        $first = $subteam->getSubteamMembers()->first();
        $leader = $subteam->getLeader() ?? $first->getUser();

        $extraData = [
            'titles' => [
                $subteam->getName(),
            ],
            'members' => $this->serializeSubteamMembers($subteam->getSubteamMembers()),
        ];

        if ($subteam->hasLeader()) {
            array_unshift($extraData['titles'], $this->translator->trans('roles.team_leader', [], 'messages'));
        }

        return $this->extractUserData($leader, $extraData);
    }

    /**
     * @param SubteamMember[]|ArrayCollection $members
     *
     * @return array
     */
    private function serializeSubteamMembers($members)
    {
        return $members
            ->filter(
                function (SubteamMember $member) {
                    return !$member->isLead();
                }
            )
            ->map(
                function (SubteamMember $member) {
                    return $this->serializeSubteamMember($member);
                }
            )
            ->getValues();
    }

    /**
     * @param SubteamMember $member
     *
     * @return array
     */
    private function serializeSubteamMember(SubteamMember $member)
    {
        return $this->extractUserData($member->getUser());
    }

    /**
     * @param UserInterface $user
     * @param array         $extraData
     *
     * @return array
     */
    private function extractUserData(UserInterface $user, array $extraData = [])
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
