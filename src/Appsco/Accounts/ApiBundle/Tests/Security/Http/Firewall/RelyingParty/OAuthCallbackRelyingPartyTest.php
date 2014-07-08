<?php

namespace Appsco\Accounts\ApiBundle\Tests\Security\Http\Firewall\RelyingParty;

use Appsco\Accounts\ApiBundle\Security\Http\Firewall\RelyingParty\OAuthCallbackRelyingParty;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OAuthCallbackRelyingPartyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldConstruct()
    {
        new OAuthCallbackRelyingParty($this->getHttpUtilsMock(), $this->getAppscoOAuthMock(), '');
    }

    /**
     * @test
     */
    public function shouldSupportRequestThatMatchesOAuthStartPathRequestAttribute()
    {
        $request = new Request();
        $request->attributes->set('oauth_callback_path', $expectedPath = '/appsco/callback');

        $httpUtilsMock = $this->getHttpUtilsMock();
        $httpUtilsMock->expects($this->once())
            ->method('checkRequestPath')
            ->with($request, $expectedPath)
            ->will($this->returnValue(true));

        $appscoOAuthMock = $this->getAppscoOAuthMock();

        $party = new OAuthCallbackRelyingParty($httpUtilsMock, $appscoOAuthMock, null);

        $this->assertTrue($party->supports($request));
    }

    /**
     * @test
     */
    public function shouldCallOAuthCallbackOnManage()
    {
        $request = new Request();
        $request->attributes->set('oauth_callback_path', $expectedPath = '/appsco/callback');

        $redirectUri = 'https://my-site.com/callback';

        $httpUtilsMock = $this->getHttpUtilsMock();
        $httpUtilsMock->expects($this->once())
            ->method('checkRequestPath')
            ->with($request, $expectedPath)
            ->will($this->returnValue(true));

        $appscoOAuthMock = $this->getAppscoOAuthMock();
        $appscoOAuthMock->expects($this->once())
            ->method('callback')
            ->with($request, $redirectUri)
            ->will($this->returnValue($expectedResponse = new Response()));

        $party = new OAuthCallbackRelyingParty($httpUtilsMock, $appscoOAuthMock, $redirectUri);

        $response = $party->manage($request);

        $this->assertSame($expectedResponse, $response);
    }


    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\Security\Http\HttpUtils
     */
    private function getHttpUtilsMock()
    {
        return $this->getMock('Symfony\Component\Security\Http\HttpUtils');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Appsco\Accounts\ApiBundle\OAuth\AppscoOAuth
     */
    private function getAppscoOAuthMock()
    {
        return $this->getMock('Appsco\Accounts\ApiBundle\OAuth\AppscoOAuth', array(), array(), '', false);
    }

} 