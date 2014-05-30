<?php

namespace Appsco\Accounts\Api\OAuth;

use Appsco\Accounts\Api\AppscoClient;
use Appsco\Accounts\Api\Model\Profile;
use Appsco\Accounts\Api\Security\Core\Authentication\AppscoToken;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;


class AppscoOAuth
{
    /** @var  AppscoClient */
    protected $client;

    /** @var  SessionInterface */
    protected $session;



    /**
     * @param AppscoClient $client
     * @param SessionInterface $session
     */
    public function __construct(AppscoClient $client, SessionInterface $session)
    {
        $this->client = $client;
        $this->session = $session;
    }



    /**
     * @param array|string[] $scope
     * @return RedirectResponse
     */
    public function start(array $scope = array(), $redirectUri = null)
    {
        $state = $this->generateState();

        $url = $this->client->getAuthorizeUrl($state, $scope, $redirectUri);

        return new RedirectResponse($url);
    }


    /**
     * @param Request $request
     * @param string|null $redirectUri
     * @throws \Symfony\Component\Security\Core\Exception\AuthenticationException
     * @return AppscoToken
     */
    public function callback(Request $request, $redirectUri = null)
    {
        $code = $request->get('code');
        $state = $request->get('state');

        $this->validateState($state);

        $this->client->getAccessData($code, $redirectUri);
        $profile = $this->client->profileRead('me');

        if (!$profile) {
            throw new AuthenticationException('Unable to get profile info from Appsco');
        }

        return $this->createToken($profile);
    }

    /**
     * @return string
     */
    protected function generateState()
    {
        $state = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);

        $this->session->set($this->getStateSessionKey(), $state);

        return $state;
    }

    /**
     * @param string $state
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     */
    protected function validateState($state)
    {
        $savedState = $this->session->get($this->getStateSessionKey());

        if ($savedState != $state) {
            throw new BadRequestHttpException('Invalid state');
        }
    }

    protected function getStateSessionKey()
    {
        return 'appsco_oauth_state';
    }

    /**
     * @param \Appsco\Accounts\Api\Model\Profile $profile
     * @return AppscoToken
     */
    protected function createToken(Profile $profile)
    {
        return new AppscoToken($profile);
    }

} 