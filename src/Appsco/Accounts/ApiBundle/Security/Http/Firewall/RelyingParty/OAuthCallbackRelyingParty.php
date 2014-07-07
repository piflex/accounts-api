<?php

namespace Appsco\Accounts\ApiBundle\Security\Http\Firewall\RelyingParty;

use Appsco\Accounts\ApiBundle\OAuth\AppscoOAuth;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\HttpUtils;

class OAuthCallbackRelyingParty implements RelyingPartyInterface
{
    /** @var  HttpUtils */
    protected $httpUtils;

    /** @var  AppscoOAuth */
    protected $oauth;

    /** @var  string */
    protected $redirectUrl;



    public function __construct(HttpUtils $httpUtils, AppscoOAuth $oauth, $redirectUrl)
    {
        $this->httpUtils = $httpUtils;
        $this->oauth = $oauth;
        $this->redirectUrl = $redirectUrl;
    }


    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return bool
     */
    public function supports(Request $request)
    {
        return $this->httpUtils->checkRequestPath($request, $request->attributes->get('oauth_callback_path'));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @throws \InvalidArgumentException if cannot manage the Request
     * @return \Symfony\Component\HttpFoundation\Response|TokenInterface|null
     */
    public function manage(Request $request)
    {
        if (false == $this->supports($request)) {
            throw new \InvalidArgumentException('Unsupported request');
        }

        return $this->oauth->callback($request, $this->redirectUrl);
    }

} 