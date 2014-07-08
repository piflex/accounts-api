<?php

namespace Appsco\Accounts\ApiBundle\Tests\Client;

use Appsco\Accounts\ApiBundle\Client\AccountsClient;
use Appsco\Accounts\ApiBundle\Model\AccessData;
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


    public function shouldGetAuthorizeUrl()
    {
        $client = $this->createClient(null, null, null);

        $url = $client->getAuthorizeUrl($state = 'asdfghzxcvbn', array(Scopes::PROFILE_READ));

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
} 