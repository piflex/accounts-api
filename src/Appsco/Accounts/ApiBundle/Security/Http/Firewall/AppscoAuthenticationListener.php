<?php

namespace Appsco\Accounts\ApiBundle\Security\Http\Firewall;

use Appsco\Accounts\ApiBundle\Error\AppscoOAuthException;
use Appsco\Accounts\ApiBundle\Security\Core\Authentication\Token\AppscoToken;
use Appsco\Accounts\ApiBundle\Security\Http\Firewall\RelyingParty\RelyingPartyInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;

class AppscoAuthenticationListener extends AbstractAuthenticationListener
{
    /** @var  RelyingPartyInterface */
    protected $relyingParty;

    protected $keysToCopy = array(
        'oauth_start_path',
        'oauth_callback_path',
    );


    /**
     * @param RelyingPartyInterface $relyingParty
     * @return $this|AppscoAuthenticationListener
     */
    public function setRelyingParty(RelyingPartyInterface $relyingParty)
    {
        $this->relyingParty = $relyingParty;

        return $this;
    }

    /**
     * @return RelyingPartyInterface
     * @throws \LogicException
     */
    protected function getRelyingParty() {
        if (false == $this->relyingParty) {
            throw new \LogicException(
                'The relying party is required for the listener work, but it was not set. Seems like miss configuration'
            );
        }
        return $this->relyingParty;
    }

    protected function requiresAuthentication(Request $request)
    {
        return $this->httpUtils->checkRequestPath($request, $this->options['oauth_start_path']) ||
            $this->httpUtils->checkRequestPath($request, $this->options['oauth_callback_path'])
        ;
    }

    /**
     * Performs authentication.
     *
     * @param Request $request A Request instance
     *
     * @return TokenInterface|Response|null The authenticated token, null if full authentication is not possible, or a Response
     *
     * @throws AuthenticationException if the authentication fails
     */
    protected function attemptAuthentication(Request $request)
    {
        $myRequest = $this->duplicateRequest($request);

        if (!$this->getRelyingParty()->supports($myRequest)) {
            return null;
        }

        try {
            $tokenOrResponse = $this->getRelyingParty()->manage($myRequest);
        } catch (AuthenticationException $ex) {
            throw $ex;
        } catch (\Exception $ex) {
            $msg = "Appsco OAuth failed";
            if ($ex instanceof AppscoOAuthException) {
                $msg .= ': '.$ex->getMessage();
            }
            throw new AuthenticationException($msg, 0, $ex);
        }

        if ($tokenOrResponse instanceof Response) {

            return $tokenOrResponse;
        }

        if ($tokenOrResponse instanceof AppscoToken) {
            try {

                $result = $this->authenticationManager->authenticate($tokenOrResponse);

                $this->setLocale($tokenOrResponse, $request);

                return $result;

            } catch (AuthenticationException $e) {
                $e->setToken($tokenOrResponse);
                throw $e;
            }
        }

        return null;
    }

    /**
     * @param AppscoToken $token
     * @param Request $request
     */
    protected function setLocale(AppscoToken $token, Request $request)
    {
        $locale = 'en';
        $user = $token->getProfile();
        if ($user) {
            $l = $user->getLocale();
            if ($l) {
                $locale = $l;
            }
        }

        $request->getSession()->set('_locale', $locale);
        $request->setLocale($locale);
    }

    /**
     * @param Request $request
     * @return Request
     */
    protected function duplicateRequest(Request $request)
    {
        $myRequest = $request->duplicate();

        foreach ($this->keysToCopy as $key) {
            $myRequest->attributes->set($key, $this->options[$key]);
        }

        return $myRequest;
    }

}