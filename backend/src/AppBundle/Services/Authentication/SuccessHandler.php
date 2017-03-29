<?php

namespace AppBundle\Services\Authentication;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Translation\TranslatorInterface;

class SuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!($user instanceof UserInterface)) {
            return;
        }

        return new JsonResponse(
            [
                'message' => $this->translator->trans(
                    'authentication.token_returned',
                    [],
                    'messages'
                ),
                'token' => $user->getApiToken(),
            ],
            JsonResponse::HTTP_OK
        );
    }
}
