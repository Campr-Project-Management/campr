<?php

namespace PortalBundle\Security\Http\Authentication;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;

class Authenticator implements SimplePreAuthenticatorInterface
{
    /**
     * @var string
     */
    private $secret;

    /**
     * WebhookAuthenticator constructor.
     *
     * @param string $secret
     */
    public function __construct(string $secret)
    {
        $this->secret = $secret;
    }

    /**
     * @param Request $request
     * @param         $providerKey
     *
     * @return PreAuthenticatedToken
     */
    public function createToken(Request $request, $providerKey)
    {
        $authorization = $request->headers->get('authorization');
        if (empty($authorization) || !is_string($authorization)) {
            throw new BadCredentialsException('Authorization must be provided.');
        }

        $peaces = explode(' ', $authorization);
        if (2 !== count($peaces) || 'bearer' !== strtolower($peaces[0])) {
            throw new BadCredentialsException('Malformed authorization provided.');
        }

        return new PreAuthenticatedToken(
            'anon.',
            $peaces[1],
            $providerKey
        );
    }

    /**
     * @param TokenInterface $token
     * @param                $providerKey
     *
     * @return bool
     */
    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }

    /**
     * @param TokenInterface        $token
     * @param UserProviderInterface $userProvider
     * @param                       $providerKey
     *
     * @return PreAuthenticatedToken
     */
    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        if ($token->getCredentials() !== $this->secret) {
            throw new CustomUserMessageAuthenticationException('Unable to authenticate based on bearer.');
        }

        return new PreAuthenticatedToken(
            'portal',
            $token->getCredentials(),
            $providerKey,
            ['ROLE_PORTAL']
        );
    }
}
