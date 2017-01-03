<?php

namespace AppBundle\Services\Authentication;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;

class Authenticator implements SimplePreAuthenticatorInterface
{
    public function createToken(Request $request, $providerKey)
    {
        $authorization = $request->headers->get('authorization');

        if (empty($authorization) || !is_string($authorization)) {
            throw new BadCredentialsException('Authorization must be provided.');
        }

        $peaces = explode(' ', $authorization);

        if (count($peaces) !== 2 || strtolower($peaces[0]) !== 'bearer') {
            throw new BadCredentialsException('Malformed authorization provided.');
        }

        return new PreAuthenticatedToken(
            'anon.',
            $peaces[1],
            $providerKey
        );
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }

    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        /** @var \AppBundle\Services\Authentication\UserProvider $userProvider */
        $user = $userProvider->loadUserByApiToken($token->getCredentials());

        if (!$user) {
            throw new CustomUserMessageAuthenticationException('Unable to authenticated based on bearer.');
        }

        return new PreAuthenticatedToken(
            $user,
            $user->getApiToken(),
            $providerKey,
            $user->getRoles()
        );
    }
}
