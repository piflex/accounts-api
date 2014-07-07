<?php

namespace Appsco\Accounts\ApiBundle\OAuth;

use Appsco\Accounts\ApiBundle\Client\AccountsClient;
use Appsco\Accounts\ApiBundle\Error\AppscoOAuthException;
use Appsco\Accounts\ApiBundle\Model\AccessData;
use Appsco\Accounts\ApiBundle\Model\Profile;
use Appsco\Accounts\ApiBundle\Security\Core\Authentication\Token\AppscoToken;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class AppscoOAuth implements AppscoOAuthInterface
{
    /** @var  AccountsClient */
    protected $client;

    /** @var  SessionInterface */
    protected $session;



    /**
     * @param AccountsClient $client
     * @param SessionInterface $session
     */
    public function __construct(AccountsClient $client, SessionInterface $session)
    {
        $this->client = $client;
        $this->session = $session;
    }


    /**
     * @return AccountsClient
     */
    public function getClient()
    {
        return $this->client;
    }





    /**
     * @param array|string[] $scope
     * @param string|null $redirectUri
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
     * @throws AppscoOAuthException
     * @return AppscoToken
     */
    public function callback(Request $request, $redirectUri = null)
    {
        $code = $request->get('code');
        $state = $request->get('state');

        $this->validateState($state);
        $this->checkError($request);

        $accessData = $this->client->getAccessData($code, $redirectUri);
        $profile = $this->client->profileRead('me');

        if (false == $profile) {
            throw new AuthenticationException('Unable to get profile info from Appsco');
        }

        return $this->createToken($accessData, $profile);
    }


    /**
     * @param Request $request
     * @throws AppscoOAuthException
     */
    protected function checkError(Request $request)
    {
        if ($error = $request->query->get('error')) {
            throw new AppscoOAuthException($error, $request->query->get('error_description'));
        }
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
     * @param \Appsco\Accounts\ApiBundle\Model\AccessData $accessData
     * @param \Appsco\Accounts\ApiBundle\Model\Profile $profile
     * @return AppscoToken
     */
    protected function createToken(AccessData $accessData, Profile $profile)
    {
        $result = new AppscoToken($profile, array(), $accessData->getAccessToken(), $accessData->getIdToken());
        return $result;
    }

} 