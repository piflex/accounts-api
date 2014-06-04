<?php

namespace Appsco\Accounts\ApiBundle\OAuth;

use Appsco\Accounts\ApiBundle\Error\AppscoOAuthException;
use Appsco\Accounts\Apibundle\Security\Core\Authentication\AppscoToken;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

interface AppscoOAuthInterface
{
    /**
     * @return \Appsco\Accounts\ApiBundle\Client\AccountsClient
     */
    public function getClient();

    /**
     * @param array|string[] $scope
     * @param string|null $redirectUri
     * @return RedirectResponse
     */
    public function start(array $scope = array(), $redirectUri = null);

    /**
     * @param Request $request
     * @param string|null $redirectUri
     * @throws \Symfony\Component\Security\Core\Exception\AuthenticationException
     * @throws AppscoOAuthException
     * @return AppscoToken
     */
    public function callback(Request $request, $redirectUri = null);

} 