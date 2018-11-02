<?php

namespace PortalBundle\Controller\Api;

use AppBundle\Entity\User;
use MainBundle\Controller\API\ApiController;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/portal/api/users")
 */
class UserController extends ApiController
{
    /**
     * @Route("", name="portal_api_user_delete", methods={"DELETE"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function deleteAction(Request $request): JsonResponse
    {
        $email = $request->request->get('email');
        if (empty($email)) {
            return $this->createApiResponse(
                [
                    'message' => 'Empty email',
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        $user = $this->findUserByEmailOr404($email);

        $logger = $this->getLogger();

        $logger->info('Delete user request received', ['email' => $email]);

        $this->get('app.remover.user')->remove($user);

        $logger->info('Delete user request success', ['email' => $email]);

        return $this->createApiResponse(
            [
                'message' => 'User successfully deleted',
            ],
            Response::HTTP_OK
        );
    }

    /**
     * @return LoggerInterface
     */
    private function getLogger(): LoggerInterface
    {
        return $this->get('monolog.logger.portal');
    }

    /**
     * @param string $email
     *
     * @return User
     */
    private function findUserByEmailOr404(string $email): User
    {
        $user = $this->get('app.repository.user')->findOneBy(['email' => $email]);
        if ($user) {
            return $user;
        }

        $this->getLogger()->error(sprintf('User "%s" not found', $email));

        throw $this->createNotFoundException(sprintf('User with email address "%s" not found', $email));
    }
}
