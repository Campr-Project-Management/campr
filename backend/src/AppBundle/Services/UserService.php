<?php

namespace AppBundle\Services;

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

    public function inviteUserToWorkspace(User $inviter, string $team, string $email)
    {
        $request = $this->httpClient->post(
            sprintf('workspaces/%s/invite-member', $team),
            [
                'headers' => [
                    'Authorization' => 'Bearer '.$inviter->getApiToken(),
                ],
                'form_params' => [
                    'email' => $email,
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

        return json_decode($data, true);
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

    public function createTeamMember(Request $request)
    {
        $multipart = [];
        foreach ($request->request->all() as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $subKey => $subValue) {
                    $multipart[] = [
                        'name' => $key.'['.(is_numeric($subKey) ? '' : $subKey).']',
                        'contents' => $subValue,
                    ];
                }
            } else {
                $multipart[] = [
                    'name' => $key,
                    'contents' => $value,
                ];
            }
        }

        if ($request->files->has('avatarFile')) {
            $avatar = $request->files->get('avatarFile');
            if (isset($avatar['file']) && $avatar['file'] instanceof UploadedFile) {
                $multipart[] = [
                    'name' => 'avatarFile[file]',
                    'contents' => fopen($avatar['file']->getRealPath(), 'r+'),
                    'filename' => $avatar['file']->getClientOriginalName(),
                ];
            }
        }

        $req = $this->httpClient->post(
            $this->router->generate('main_api_team_members_create'),
            [
                'http_errors' => false,
                'headers' => [
                    'Authorization' => $request->headers->get('authorization'),
                ],
                'multipart' => $multipart,
            ])
        ;

        return in_array($req->getStatusCode(), [Response::HTTP_CREATED, Response::HTTP_OK]);
    }
}
