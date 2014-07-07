<?php

namespace Appsco\Accounts\ApiBundle\Security\Core\Authentication\Provider;

use Appsco\Accounts\ApiBundle\Security\Core\Authentication\Token\AppscoToken;
use Appsco\Accounts\ApiBundle\Security\Core\User\AppscoUserProviderInterface;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\AuthenticationServiceException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AppscoAuthenticationProvider implements AuthenticationProviderInterface
{
    /** @var  AppscoUserProviderInterface */
    protected $userProvider;

    /** @var null|\Symfony\Component\Security\Core\User\UserCheckerInterface */
    protected $userChecker;


    /**
     * @param AppscoUserProviderInterface $userProvider
     * @param UserCheckerInterface $userChecker
     */
    public function __construct(AppscoUserProviderInterface $userProvider, UserCheckerInterface $userChecker)
    {
        $this->userChecker = $userChecker;
        $this->userProvider = $userProvider;
    }



    /**
     * Checks whether this provider supports the given token.
     *
     * @param TokenInterface $token A TokenInterface instance
     *
     * @return bool    true if the implementation supports the Token, false otherwise
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof AppscoToken;
    }


    /**
     * Attempts to authenticate a TokenInterface object.
     *
     * @param TokenInterface $token The TokenInterface instance to authenticate
     *
     * @return TokenInterface An authenticated TokenInterface instance, never null
     *
     * @throws AuthenticationException if the authentication fails
     */
    public function authenticate(TokenInterface $token)
    {
        if (false == $this->supports($token)) {
            throw new AuthenticationException('Unsupported token');
        }

        /** @var AppscoToken $token */

        try {
            $user = $this->getUser($token);

            $authenticatedToken = $this->createAuthenticatedToken($user, $token);

            return $authenticatedToken;

        } catch (AuthenticationException $ex) {
            throw $ex;
        } catch (\Exception $ex) {
            throw new AuthenticationServiceException($ex->getMessage(), (int) $ex->getCode(), $ex);
        }
    }


    /**
     * @param UserInterface $user
     * @param AppscoToken $token
     * @return AppscoToken
     */
    protected function createAuthenticatedToken($user, AppscoToken $token)
    {
        if ($user instanceof UserInterface && $this->userChecker) {
            $this->userChecker->checkPostAuth($user);
        }

        $authenticatedToken = new AppscoToken($user, $user->getRoles(), $token->getAccessToken(), $token->getIdToken());

        return $authenticatedToken;
    }

    /**
     * @param AppscoToken $token
     * @throws \RuntimeException
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    protected function getUser(AppscoToken $token)
    {
        try {
            $user = $this->userProvider->loadUserByUsername($token->getProfile()->getEmail());
        } catch (UsernameNotFoundException $ex) {
            $user = $this->createUser($token);
        }

        if (false == $user instanceof UserInterface) {
            throw new \RuntimeException('User provider did not return an implementation of user interface.');
        }

        return $user;
    }

    /**
     * @param AppscoToken $token
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    protected function createUser(AppscoToken $token)
    {
        $user = $this->userProvider->create($token);

        return $user;
    }
} 