<?php

namespace AppBundle\Services\Authentication;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Translation\TranslatorInterface;

class FailureHandler implements AuthenticationFailureHandlerInterface
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $message = $exception instanceof DisabledException
            ? $this->translator->trans('authentication.account_disabled', [], 'messages')
            : $this->translator->trans('authentication.bad_credentials', [], 'messages')
        ;

        return new JsonResponse(
            [
                'message' => $message,
            ],
            JsonResponse::HTTP_UNAUTHORIZED
        );
    }
}
