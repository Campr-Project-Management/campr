<?php

namespace AppBundle\Services;

use AppBundle\Entity\DistributionList;
use AppBundle\Entity\ProjectUser;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

/**
 * Class UserService
 * Service used to handle user related actions.
 */
class UserService extends BaseClientService
{
    private $em;

    private $encoder;

    private $router;

    public function __construct(RequestStack $requestStack, $mainDomain, EntityManager $em, UserPasswordEncoder $encoder, Router $router)
    {
        parent::__construct($requestStack, $mainDomain);

        $this->em = $em;
        $this->encoder = $encoder;
        $this->router = $router;
    }

    /**
     * Returns a random token for user activation/reset.
     *
     * @return mixed
     */
    public function generateActivationResetToken()
    {
        return substr(md5(microtime()), rand(0, 26), 6);
    }

    /**
     * Synchronize user from main site with user from secondary website.
     *
     * @param User $user
     *
     * @return User
     */
    public function syncUser(User $user)
    {
        $request = $this->httpClient->get(
            'user',
            [
                'headers' => [
                    'Authorization' => 'Bearer '.$user->getApiToken(),
                ],
            ]
        );

        if ($request->getStatusCode() !== 200) {
            throw new NotFoundHttpException();
        }

        $body = $request->getBody();
        $data = '';
        while (!$body->eof()) {
            $data .= $body->read(1024);
        }
        $data = json_decode($data, true);

        $user->setUsername($data['username']);
        $user->setFirstName($data['firstName']);
        $user->setLastName($data['lastName']);
        $user->setPhone($data['phone']);
        $user->setRoles($data['roles']);
        $user->setWidgetSettings($data['widgetSettings']);
        $user->setIsEnabled($data['isEnabled']);
        $user->setIsSuspended($data['isSuspended']);
        $user->setFacebook($data['facebook']);
        $user->setTwitter($data['twitter']);
        $user->setInstagram($data['instagram']);
        $user->setGplus($data['gplus']);
        $user->setLinkedIn($data['linkedIn']);
        $user->setMedium($data['medium']);
        $user->setUpdatedAt(new \DateTime($data['updatedAt']));
        $user->setAvatar($data['avatar']);

        return $user;
    }

    /**
     * @param $subdomain
     * @param $data
     *
     * @return ProjectUser|string
     */
    public function createTeamMember($subdomain, $data, $token)
    {
        $req = $this->teamMemberCreateRequest($subdomain, $data, $token);
        $body = $req->getBody();
        $reqBody = '';
        while (!$body->eof()) {
            $reqBody .= $body->read(1024);
        }
        $reqBody = json_decode($reqBody, true);
        if ($req->getStatusCode() !== Response::HTTP_CREATED) {
            return $reqBody;
        }
        $avatar = explode('/', $reqBody['avatar']);
        $user = (new User())
            ->setUsername($reqBody['username'])
            ->setFirstName($reqBody['firstName'])
            ->setLastName($reqBody['lastName'])
            ->setEmail($reqBody['email'])
            ->setPhone($reqBody['phone'])
            ->setRoles($reqBody['roles'])
            ->setPlainPassword(microtime(true))
            ->setApiToken($reqBody['apiToken'])
            ->setFacebook($reqBody['facebook'])
            ->setTwitter($reqBody['twitter'])
            ->setLinkedIn($reqBody['linkedIn'])
            ->setGplus($reqBody['gplus'])
            ->setWidgetSettings($reqBody['widgetSettings'])
            ->setAvatar(!empty($avatar) ? end($avatar) : null)
        ;

        $projectUser = (new ProjectUser())
            ->setUser($user)
            ->setProject($data['project'])
            ->setCompany(isset($data['company']) ? $data['company'] : null)
            ->setShowInResources(isset($data['showInResources']) ? $data['showInResources'] : false)
            ->setShowInOrg(isset($data['showInOrg']) ? $data['showInOrg'] : false)
            ->setShowInRaci(isset($data['showInRaci']) ? $data['showInRaci'] : false)
        ;

        if (isset($data['roles'])) {
            $this->addRolesToProjectUser($projectUser, $data['roles']);
        }

        if (isset($data['departments'])) {
            $this->addDepartmentsToProjectUser($projectUser, $data['departments']);
        }
        if (isset($data['distributions'])) {
            $this->addUserToDistributionLists($user, $data['distributions']);
        }

        $this->em->persist($user);
        $this->em->persist($projectUser);
        $this->em->flush();

        return $projectUser;
    }

    private function teamMemberCreateRequest($subdomain, $data, $token)
    {
        $uri = $this->router->generate('main_api_team_members_create');
        $multipart = [
            [
                'name' => 'username',
                'contents' => $data['name'],
            ],
            [
                'name' => 'email',
                'contents' => $data['email'],
            ],
            [
                'name' => 'phone',
                'contents' => isset($data['phone']) ? $data['phone'] : null,
            ],
            [
                'name' => 'facebook',
                'contents' => isset($data['facebook']) ? $data['facebook'] : null,
            ],
            [
                'name' => 'twitter',
                'contents' => isset($data['twitter']) ? $data['twitter'] : null,
            ],
            [
                'name' => 'linkedin',
                'contents' => isset($data['linkedIn']) ? $data['linkedIn'] : null,
            ],
            [
                'name' => 'gplus',
                'contents' => isset($data['gplus']) ? $data['gplus'] : null,
            ],
            [
                'name' => 'teamSlug',
                'contents' => $subdomain,
            ],
        ];
        if ($data['files']['avatar']) {
            /** @var UploadedFile $file */
            $file = $data['files']['avatar'];
            $multipart[] = [
                'name' => 'avatarFile[file]',
                'contents' => file_get_contents($file->getRealPath()),
                'filename' => $file->getClientOriginalName(),
            ];
        }
        $req = $this->httpClient->post(
            $uri,
            [
                'http_errors' => false,
                'headers' => [
                    'Authorization' => sprintf('Bearer %s', $token),
                ],
                'multipart' => $multipart,
            ])
        ;
        return $req;
    }

    /**
     * @param ProjectUser $projectUser
     * @param array       $roles
     */
    private function addRolesToProjectUser(ProjectUser $projectUser, array $roles)
    {
        foreach ($roles as $roleId) {
            $projectRole = $this
                ->em
                ->getRepository(ProjectRole::class)
                ->find($roleId)
            ;

            if ($projectRole) {
                $projectUser->addProjectRole($projectRole);
            }
        }
    }

    /**
     * @param ProjectUser $projectUser
     * @param array       $departments
     */
    private function addDepartmentsToProjectUser(ProjectUser $projectUser, array $departments)
    {
        foreach ($departments as $departmentId) {
            $projectDepartment = $this
                ->em
                ->getRepository(ProjectDepartment::class)
                ->find($departmentId)
            ;

            if ($projectDepartment) {
                $projectUser->addProjectDepartment($projectDepartment);
            }
        }
    }

    private function addUserToDistributionLists(User $user, array $distributionLists)
    {
        foreach ($distributionLists as $distributionListId) {
            $distributionList = $this
                ->em
                ->getRepository(DistributionList::class)
                ->find($distributionListId)
            ;

            if ($distributionList) {
                $distributionList->addUser($user);
                $this->em->persist($distributionList);
            }
        }
    }
}
