<?php

namespace Appsco\Accounts\ApiBundle\Tests\Security\Http\Firewall\RelyingParty;

use Appsco\Accounts\ApiBundle\OAuth\Scopes;
use Appsco\Accounts\ApiBundle\Security\Http\Firewall\RelyingParty\OAuthStartRelyingParty;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;

class OAuthStartRelyingPartyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldConstruct()
    {
        $httpUtilsMock = $this->getHttpUtilsMock();
        $appscoOAuthMock = $this->getAppscoOAuthMock();

        new OAuthStartRelyingParty($httpUtilsMock, $appscoOAuthMock, array(), null);
    }


    /**
     * @test
     */
    public function shouldSupportRequestThatMatchesOAuthStartPathRequestAttribute()
    {
        $request = new Request();
        $request->attributes->set('oauth_start_path', $expectedPath = '/appsco/start');

        $httpUtilsMock = $this->getHttpUtilsMock();
        $httpUtilsMock->expects($this->once())
            ->method('checkRequestPath')
            ->with($request, $expectedPath)
            ->will($this->returnValue(true));

        $appscoOAuthMock = $this->getAppscoOAuthMock();

        $party = new OAuthStartRelyingParty($httpUtilsMock, $appscoOAuthMock, array(), null);

        $this->assertTrue($party->supports($request));
    }

    /**
     * @test
     */
    public function shouldSupportRequestIfHttpUtilsDoesNotMatch()
    {
        $request = new Request();
        $request->attributes->set('oauth_start_path', $expectedPath = '/appsco/start');

        $httpUtilsMock = $this->getHttpUtilsMock();
        $httpUtilsMock->expects($this->once())
            ->method('checkRequestPath')
            ->with($request, $expectedPath)
            ->will($this->returnValue(false));

        $appscoOAuthMock = $this->getAppscoOAuthMock();

        $party = new OAuthStartRelyingParty($httpUtilsMock, $appscoOAuthMock, array(), null);

        $this->assertFalse($party->supports($request));
    }


    /**
     * @test
     */
    public function shouldCallOAuthStartOnManage()
    {
        $request = new Request();
        $request->attributes->set('oauth_start_path', '/appsco/start');

        $scope = array(Scopes::PROFILE_READ);
        $redirectUri = 'https://my-site.com/callback';

        $httpUtilsMock = $this->getHttpUtilsMock();
        $httpUtilsMock->expects($this->any())
            ->method('checkRequestPath')
            ->will($this->returnValue(true));

        $appscoOAuthMock = $this->getAppscoOAuthMock();
        $appscoOAuthMock->expects($this->once())
            ->method('start')
            ->with($scope, $redirectUri)
            ->will($this->returnValue($expectedResponse = new Response()));

        $party = new OAuthStartRelyingParty($httpUtilsMock, $appscoOAuthMock, $scope, $redirectUri);

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