<?php

namespace Appsco\Accounts\ApiBundle\Tests\Client;

use Appsco\Accounts\ApiBundle\Client\AccountsClient;
use Appsco\Accounts\ApiBundle\Model\AccessData;
use Appsco\Accounts\ApiBundle\Model\CertificateList;
use Appsco\Accounts\ApiBundle\Model\Profile;
use Appsco\Accounts\ApiBundle\OAuth\Scopes;

class AccountsClientTest extends \PHPUnit_Framework_TestCase
{
    private $expectedScheme = 'https';
    private $expectedDomain = 'accounts.dev.appsco.com';
    private $expectedSufix = '';
    private $expectedRedirectUri = 'https://my-site.com/appsco/callback';
    private $expectedClientId = '123123';
    private $expectedClientSecret = '4564564564546454546';
    private $expectedAuthType = AccountsClient::AUTH_TYPE_ACCESS_TOKEN;

    /**
     * @test
     */
    public function shouldConstruct()
    {
        $this->createClient(null, null, null);
    }

    /**
     * @test
     */
    public function shouldSetAccessToken()
    {
        $client = $this->createClient(null, null, null);
        $client->setAccessToken($expectedValue = '1234567890');
        $this->assertEquals($expectedValue, $client->getAccessToken());
    }

    /**
     * @test
     */
    public function shouldSetClientId()
    {
        $client = $this->createClient(null, null, null);
        $client->setClientId($expectedValue = '1234567890');
        $this->assertEquals($expectedValue, $client->getClientId());
    }

    /**
     * @test
     */
    public function shouldSetClientSecret()
    {
        $client = $this->createClient(null, null, null);
        $client->setClientSecret($expectedValue = '1234567890');
        $this->assertEquals($expectedValue, $client->getClientSecret());
    }

    /**
     * @test
     */
    public function shouldSetDefaultRedirectUri()
    {
        $client = $this->createClient(null, null, null);
        $client->setDefaultRedirectUri($expectedValue = 'https://example.com');
        $this->assertEquals($expectedValue, $client->getDefaultRedirectUri());
    }

    /**
     * @test
     */
    public function shouldSetAuthType()
    {
        $client = $this->createClient(null, null, null);
        $client->setAuthType($expectedValue = AccountsClient::AUTH_TYPE_REQUEST);
        $this->assertEquals($expectedValue, $client->getAuthType());
    }

    /**
     * @test
     */
    public function shouldGetAuthorizeUrl()
    {
        $client = $this->createClient(null, null, null);

        $url = $client->getAuthorizeUrl($state = 'asdfghzxcvbn');

        $urlParts = parse_url($url);
        $this->assertEquals($this->expectedScheme, $urlParts['scheme']);
        $this->assertEquals($this->expectedDomain, $urlParts['host']);
        $this->assertEquals('/oauth/authorize', $urlParts['path']);

        $query = array();
        parse_str($urlParts['query'], $query);

        $this->assertEquals($state, $query['state']);
        $this->assertEquals($this->expectedClientId, $query['client_id']);
        $this->assertEquals(Scopes::PROFILE_READ, $query['scope']);
        $this->assertEquals($this->expectedRedirectUri, $query['redirect_uri']);
        $this->assertEquals('code', $query['response_type']);
    }


    /**
     * @test
     */
    public function shouldGetAccessData()
    {
        $code = '123qweasd';
        $expectedUrl = 'https://accounts.dev.appsco.com/api/v1/token/get';
        $postData = array(
            'client_id' => $this->expectedClientId,
            'client_secret' => $this->expectedClientSecret,
            'code' => $code,
            'redirect_uri' => $this->expectedRedirectUri
        );

        $expectedAccessData = new AccessData();
        $expectedAccessData->setAccessToken('some_access_token');

        $httpClientMock = $this->getHttpClientMock();
        $httpClientMock->expects($this->once())
            ->method('post')
            ->with($expectedUrl, array(), $postData, null, array())
            ->will($this->returnValue($expectedJson = 'json'));
        $httpClientMock->expects($this->any())
            ->method('getStatusCode')
            ->will($this->returnValue(200));

        $serializerMock = $this->getSerializerMock();
        $serializerMock->expects($this->once())
            ->method('deserialize')
            ->with($expectedJson)
            ->will($this->returnValue($expectedAccessData));

        $client = $this->createClient($httpClientMock, $serializerMock, null);

        $accessData = $client->getAccessData($code);
        $this->assertSame($expectedAccessData, $accessData);

        $this->assertEquals($expectedAccessData->getAccessToken(), $client->getAccessToken());
    }


    /**
     * @test
     */
    public function shouldCallProfileRead()
    {
        $expectedAccessToken = '123qweasd123qweasd';
        $expectedUrl = 'https://accounts.dev.appsco.com/api/v1/profile/me';

        $expectedProfile = new Profile();

        $httpClientMock = $this->getHttpClientMock();
        $httpClientMock->expects($this->once())
            ->method('get')
            ->with($expectedUrl, array(), array('Authorization: token '.$expectedAccessToken))
            ->will($this->returnValue($expectedJson = 'json'));
        $httpClientMock->expects($this->any())
            ->method('getStatusCode')
            ->will($this->returnValue(200));

        $serializerMock = $this->getSerializerMock();
        $serializerMock->expects($this->once())
            ->method('deserialize')
            ->with($expectedJson)
            ->will($this->returnValue($expectedProfile));

        $client = $this->createClient($httpClientMock, $serializerMock, null);
        $client->setAccessToken($expectedAccessToken);

        $profile = $client->profileRead();
        $this->assertSame($expectedProfile, $profile);
    }

    /**
     * @test
     */
    public function shouldCallCertificatesGet()
    {
        $clientID = 'some_client_id';
        $expectedAccessToken = '123qweasd123qweasd';
        $expectedUrl = 'https://accounts.dev.appsco.com/api/v1/certificate/'.$clientID;

        $expectedCertificateList = new CertificateList();

        $httpClientMock = $this->getHttpClientMock();
        $httpClientMock->expects($this->once())
            ->method('get')
            ->with($expectedUrl, array(), array('Authorization: token '.$expectedAccessToken))
            ->will($this->returnValue($expectedJson = 'json'));
        $httpClientMock->expects($this->any())
            ->method('getStatusCode')
            ->will($this->returnValue(200));

        $serializerMock = $this->getSerializerMock();
        $serializerMock->expects($this->once())
            ->method('deserialize')
            ->with($expectedJson)
            ->will($this->returnValue($expectedCertificateList));

        $client = $this->createClient($httpClientMock, $serializerMock, null);
        $client->setAccessToken($expectedAccessToken);

        $certificateList = $client->certificateGet($clientID);
        $this->assertSame($expectedCertificateList, $certificateList);
    }

    /**
     * @param $httpClientMock
     * @param $serializerMock
     * @param $loggerMock
     * @return AccountsClient
     */
    private function createClient($httpClientMock, $serializerMock, $loggerMock)
    {
        if (null == $httpClientMock) {
            $httpClientMock = $this->getHttpClientMock();
        }
        if (null == $serializerMock) {
            $serializerMock = $this->getSerializerMock();
        }
        if (null == $loggerMock) {
            $loggerMock = $this->getLoggerMock();
        }
        $client = new AccountsClient(
            $httpClientMock,
            $serializerMock,
            $this->expectedScheme,
            $this->expectedDomain,
            $this->expectedSufix,
            $this->expectedRedirectUri,
            $this->expectedClientId,
            $this->expectedClientSecret,
            $this->expectedAuthType,
            $loggerMock
        );

        return $client;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\BWC\Share\Net\HttpClient\HttpClientInterface
     */
    private function getHttpClientMock()
    {
        return $this->getMock('BWC\Share\Net\HttpClient\HttpClientInterface');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\JMS\Serializer\SerializerInterface
     */
    private function getSerializerMock()
    {
        return $this->getMock('JMS\Serializer\SerializerInterface');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Psr\Log\LoggerInterface
     */
    private function getLoggerMock()
    {
        return $this->getMock('Psr\Log\LoggerInterface');
    }
} 