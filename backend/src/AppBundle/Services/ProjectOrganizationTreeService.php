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
                'title' => $this->translator->trans('roles.project_sponsor', [], 'messages'),
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
                'title' => $this->translator->trans('roles.project_manager', [], 'messages'),
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
                        'title' => $projectDepartment->getName(),
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
                return $this->extractUserData(
                    $subteam->getSubteamMembers()->first()->getUser(),
                    [
                        'title' => $subteam->getName(),
                        'children' => $subteam
                            ->getSubteamMembers()
                            ->map(function (SubteamMember $subteamMember) {
                                return $this->extractUserData($subteamMember->getUser());
                            }),
                    ]
                );
            })
            ->getValues()
        ;
    }

    private function extractUserData(User $user, array $extraData = [])
    {
        return array_merge([
            'id' => $user->getId(),
            'fullName' => $user->getFullName(),
            'avatar' => $user->getAvatar(),
            'gravatar' => $user->getGravatar(),
            'facebook' => $user->getFacebook(),
            'twitter' => $user->getTwitter(),
            'instagram' => $user->getInstagram(),
            'gPlus' => $user->getGplus(),
            'linkedIn' => $user->getLinkedIn(),
            'medium' => $user->getMedium(),
        ], $extraData);
    }
}
