<?php

namespace Appsco\Accounts\ApiBundle\Tests\OAuth;

use Appsco\Accounts\ApiBundle\Model\AccessData;
use Appsco\Accounts\ApiBundle\Model\Profile;
use Appsco\Accounts\ApiBundle\OAuth\AppscoOAuth;
use Symfony\Component\HttpFoundation\Request;

class AppscoOAuthTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldConstruct()
    {
        new AppscoOAuth(
            $this->getAccountsClientMock(),
            $this->getSessionMock()
        );
    }


    /**
     * @test
     */
    public function testStart()
    {
        $expectedState = '';
        $expectedUrl = 'http://accounts.dev.appsco.com';

        $clientMock = $this->getAccountsClientMock();
        $clientMock->expects($this->once())
            ->method('getAuthorizeUrl')
            ->will($this->returnCallback(function($state, $scope, $redirectUri) use (&$expectedState, $expectedUrl) {
                $this->assertEquals($expectedState, $state);
                $this->assertEquals(array(), $scope);
                return $expectedUrl;
            }));

        $sessionMock = $this->getSessionMock();
        $sessionMock->expects($this->once())
            ->method('set')
            ->will($this->returnCallback(function($key, $value) use (&$expectedState) {
                $this->assertEquals('appsco_oauth_state', $key);
                $this->assertTrue(is_string($value));
                $this->assertTrue(strlen($value)>20);
                $expectedState = $value;
            }));

        $oauth = new AppscoOAuth($clientMock, $sessionMock);

        $response = $oauth->start();

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse', $response);
        $this->assertEquals($expectedUrl, $response->getTargetUrl());
    }


    /**
     * @test
     */
    public function testCallback()
    {
        $expectedState = 'some_random_state';
        $expectedCode = 'returned_oauth_code';

        $request = new Request(array(
            'code' => $expectedCode,
            'state' => $expectedState
        ));

        $expectedAccessData = new AccessData();
        $expectedAccessData->setAccessToken($expectedAccessToken = 'access_token');
        $expectedAccessData->setIdToken($expectedIdToken = 'id_token');

        $expectedProfile = new Profile();

        $clientMock = $this->getAccountsClientMock();
        $clientMock->expects($this->once())
            ->method('getAccessData')
            ->with($expectedCode, null)
            ->will($this->returnValue($expectedAccessData));
        $clientMock->expects($this->once())
            ->method('profileRead')
            ->with('me')
            ->will($this->returnValue($expectedProfile));

        $sessionMock = $this->getSessionMock();
        $sessionMock->expects($this->once())
            ->method('get')
            ->with('appsco_oauth_state')
            ->will($this->returnValue($expectedState));

        $oauth = new AppscoOAuth($clientMock, $sessionMock);

        $token = $oauth->callback($request);

        $this->assertInstanceOf('Appsco\Accounts\ApiBundle\Security\Core\Authentication\Token\AppscoToken', $token);
        $this->assertEquals($expectedProfile, $token->getProfile());
        $this->assertEquals($expectedAccessToken, $token->getAccessToken());
        $this->assertEquals($expectedIdToken, $token->getIdToken());
    }



    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Appsco\Accounts\ApiBundle\Client\AccountsClient
     */
    private function getAccountsClientMock()
    {
        return $this->getMock('Appsco\Accounts\ApiBundle\Client\AccountsClient', array(), array(), '', false);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    private function getSessionMock()
    {
        return $this->getMock('Symfony\Component\HttpFoundation\Session\SessionInterface');
    }
} 