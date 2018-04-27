<?php

namespace AppBundle\Services;

use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectDepartment;
use AppBundle\Entity\Subteam;
use AppBundle\Entity\SubteamMember;
use AppBundle\Entity\User;
use Symfony\Component\Translation\TranslatorInterface;

class ProjectOrganizationTreeService
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
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

        return [$this->extractUserData(
            current($project->getProjectManagers()),
            [
                'titles' => [
                    $this->translator->trans('roles.project_manager', [], 'messages'),
                ],
                'children' => $this->getDepartmentData($project),
            ]
        )];
    }

    private function getDepartmentData(Project $project)
    {
        return $project
            ->getProjectDepartments()
            ->filter(function (ProjectDepartment $projectDepartment) {
                return $projectDepartment->getProjectUsers()->count() > 0;
            })
            ->map(function (ProjectDepartment $projectDepartment) {
                return $this->extractUserData(
                    $projectDepartment->getProjectUsers()->first()->getUser(),
                    [
                        'titles' => [
                            $projectDepartment->getName(),
                        ],
                        'children' => $this->getSubteamData($projectDepartment),
                    ]
                );
            })
            ->getValues()
        ;
    }

    private function getSubteamData(ProjectDepartment $projectDepartment)
    {
        return $projectDepartment
            ->getSubteams()
            ->filter(function (Subteam $subteam) {
                return $subteam->getSubteamMembers()->count() > 0;
            })
            ->map(function (Subteam $subteam) {
                $manager = $subteam->getSubteamMembers()
                    ->filter(function (SubteamMember $subteamMember) {
                        return $subteamMember->getIsLead();
                    })
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
//                            ->filter(function (SubteamMember $subteamMember) use ($manager) {
//                                return $subteamMember->getUser() && $subteamMember->getUser() !== $manager->getUser();
//                            })
                            ->map(function (SubteamMember $subteamMember) {
                                return $this->extractUserData(
                                    $subteamMember->getUser(),
                                    [
                                        'titles' => [
                                            $this->translator->trans('roles.team_member', [], 'messages'),
                                        ],
                                    ]
                                );
                            }),
                    ]
                );
            })
            ->filter(function (array $data) {
                return count($data) > 0;
            })
            ->getValues()
        ;
    }

    private function extractUserData(User $user, array $extraData = [])
    {
        return array_merge([
            'id' => $user->getId(),
            'fullName' => $user->getFullName(),
            'email' => $user->getEmail(),
            'avatar' => $user->getAvatar(),
            'gravatar' => $user->getGravatar(),
            'facebook' => $user->getFacebook(),
            'twitter' => $user->getTwitter(),
            'instagram' => $user->getInstagram(),
            'gPlus' => $user->getGplus(),
            'linkedIn' => $user->getLinkedIn(),
            'medium' => $user->getMedium(),
            'titles' => [
                $this->translator->trans('roles.team_member', [], 'messages'),
            ],
        ], $extraData);
    }
}
