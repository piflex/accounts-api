<?php

namespace Appsco\Accounts\ApiBundle\Tests\Security\Core\Authentication\Provider;

use Appsco\Accounts\ApiBundle\Model\Profile;
use Appsco\Accounts\ApiBundle\Security\Core\Authentication\Provider\AppscoAuthenticationProvider;
use Appsco\Accounts\ApiBundle\Security\Core\Authentication\Token\AppscoToken;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\User;

class AppscoAuthenticationProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldConstruct()
    {
        new AppscoAuthenticationProvider($this->getUserProviderMock(), $this->getUserCheckerMock());
    }

    /**
     * @test
     */
    public function shouldSupportAppscoToken()
    {
        $provider = new AppscoAuthenticationProvider($this->getUserProviderMock(), $this->getUserCheckerMock());
        $this->assertTrue($provider->supports(new AppscoToken(new User('username', ''), array(), new Profile())));
    }

    /**
     * @test
     */
    public function shouldNotSupportNonAppscoToken()
    {
        $provider = new AppscoAuthenticationProvider($this->getUserProviderMock(), $this->getUserCheckerMock());
        $this->assertFalse($provider->supports(new UsernamePasswordToken('user', '', 'key')));
    }

    /**
     * @test
     */
    public function shouldAuthenticate()
    {
        $expectedEmail = 'email@example.com';

        $expectedUser = new User($expectedEmail, '');

        $profile = new Profile();
        $profile->setEmail($expectedEmail);

        $expectedAccessToken = 'access_token';
        $expectedIdToken = 'id_token';

        $token = new AppscoToken($profile, array(), $profile, $expectedAccessToken, $expectedIdToken);

        $userProviderMock = $this->getUserProviderMock();
        $userProviderMock->expects($this->once())
            ->method('loadUserByUsername')
            ->with($expectedEmail)
            ->will($this->returnValue($expectedUser));

        $userCheckerMock = $this->getUserCheckerMock();
        $userCheckerMock->expects($this->once())
            ->method('checkPostAuth')
            ->with($expectedUser);

        $provider = new AppscoAuthenticationProvider($userProviderMock, $userCheckerMock);

        /** @var AppscoToken $authenticatedToken */
        $authenticatedToken = $provider->authenticate($token);

        $this->assertInstanceOf('Appsco\Accounts\ApiBundle\Security\Core\Authentication\Token\AppscoToken', $authenticatedToken);
        $this->assertSame($expectedUser, $authenticatedToken->getUser());
        $this->assertSame($profile, $authenticatedToken->getProfile());
        $this->assertEquals($expectedAccessToken, $authenticatedToken->getAccessToken());
        $this->assertEquals($expectedIdToken, $authenticatedToken->getIdToken());
    }


    /**
     * @test
     * @expectedException \Symfony\Component\Security\Core\Exception\AuthenticationException
     * @expectedExceptionMessage Unsupported token
     */
    public function shouldThrowOnUnsupportedToken()
    {
        $userProviderMock = $this->getUserProviderMock();
        $userCheckerMock = $this->getUserCheckerMock();
        $provider = new AppscoAuthenticationProvider($userProviderMock, $userCheckerMock);
        $token = new UsernamePasswordToken('user', '', 'key');
        $provider->authenticate($token);
    }


    /**
     * @test
     */
    public function shouldCreateUserOnUsernameNotFound()
    {
        $expectedEmail = 'email@example.com';

        $expectedUser = new User($expectedEmail, '');

        $profile = new Profile();
        $profile->setEmail($expectedEmail);

        $expectedAccessToken = 'access_token';
        $expectedIdToken = 'id_token';

        $token = new AppscoToken($profile, array(), $profile, $expectedAccessToken, $expectedIdToken);

        $userProviderMock = $this->getUserProviderMock();
        $userProviderMock->expects($this->once())
            ->method('loadUserByUsername')
            ->with($expectedEmail)
            ->will($this->throwException(new UsernameNotFoundException()));
        $userProviderMock->expects($this->once())
            ->method('create')
            ->with($token)
            ->will($this->returnValue($expectedUser));

        $userCheckerMock = $this->getUserCheckerMock();
        $userCheckerMock->expects($this->once())
            ->method('checkPostAuth')
            ->with($expectedUser);

        $provider = new AppscoAuthenticationProvider($userProviderMock, $userCheckerMock);

        /** @var AppscoToken $authenticatedToken */
        $authenticatedToken = $provider->authenticate($token);

        $this->assertInstanceOf('Appsco\Accounts\ApiBundle\Security\Core\Authentication\Token\AppscoToken', $authenticatedToken);
        $this->assertSame($expectedUser, $authenticatedToken->getUser());
        $this->assertSame($profile, $authenticatedToken->getProfile());
        $this->assertEquals($expectedAccessToken, $authenticatedToken->getAccessToken());
        $this->assertEquals($expectedIdToken, $authenticatedToken->getIdToken());
    }


    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Appsco\Accounts\ApiBundle\Security\Core\User\AppscoUserProviderInterface
     */
    private function getUserProviderMock()
    {
        return $this->getMock('Appsco\Accounts\ApiBundle\Security\Core\User\AppscoUserProviderInterface');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\Security\Core\User\UserCheckerInterface
     */
    private function getUserCheckerMock()
    {
        return $this->getMock('Symfony\Component\Security\Core\User\UserCheckerInterface');
    }

} 