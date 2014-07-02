<?php

namespace Appsco\Accounts\ApiBundle\Client;

use Appsco\Accounts\ApiBundle\Model\AccessData;
use Appsco\Accounts\ApiBundle\Model\CertificateList;
use Appsco\Accounts\ApiBundle\Model\Profile;
use Appsco\Accounts\ApiBundle\Model\User;
use Appsco\Dashboard\Ket\MainBundle\Model\AuthType;
use BWC\Share\Net\HttpClient\HttpClientInterface;
use BWC\Share\Net\HttpStatusCode;
use JMS\Serializer\Serializer;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Exception\RuntimeException;
use Symfony\Component\Serializer\Exception\LogicException;

class AccountsClient
{
    const AUTH_TYPE_ACCESS_TOKEN = 1;
    const AUTH_TYPE_BASIC_AUTH = 2;
    const AUTH_TYPE_REQUEST = 3;


    /** @var  HttpClientInterface */
    protected $httpClient;

    /** @var string */
    protected $scheme = 'https';

    /** @var  string */
    protected $domain = 'accounts.appsco.com';

    /** @var string */
    protected $sufix = '';

    /** @var  Serializer */
    protected $serializer;

    /** @var  string */
    protected $defaultRedirectUri;

    /** @var  string */
    protected $accessToken = null;

    /** @var  string */
    protected $clientId;

    /** @var  string */
    protected $clientSecret;

    /** @var  LoggerInterface|null */
    protected $logger;

    /** @var integer */
    protected $authType;

    public function __construct(
        HttpClientInterface $httpClient,
        Serializer $serializer,
        $scheme,
        $domain,
        $sufix,
        $defaultRedirectUri,
        $clientId,
        $clientSecret,
        $authType,
        LoggerInterface $logger = null
    ) {
        $this->httpClient = $httpClient;
        $this->serializer = $serializer;
        $this->scheme = $scheme;
        $this->domain = $domain;
        $this->sufix = $sufix;
        $this->defaultRedirectUri = $defaultRedirectUri;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->authType = $authType;
        $this->logger = $logger;
    }


    /**
     * @param string $accessToken
     * @return $this|AccountsClient
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @param string $clientId
     * @return $this|AccountsClient
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
        return $this;
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param string $clientSecret
     * @return $this|AccountsClient
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
        return $this;
    }

    /**
     * @return string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * @param string $redirectUri
     * @return $this|AccountsClient
     */
    public function setDefaultRedirectUri($redirectUri)
    {
        $this->defaultRedirectUri = $redirectUri;
        return $this;
    }

    /**
     * @return string
     */
    public function getDefaultRedirectUri()
    {
        return $this->defaultRedirectUri;
    }

    /**
     * @param int $authType
     * @return $this
     */
    public function setAuthType($authType)
    {
        $this->authType = $authType;
        return $this;
    }

    /**
     * @return int
     */
    public function getAuthType()
    {
        return $this->authType;
    }

    /**
     * @param string $state
     * @param array|string[] $scope
     * @param string $redirectUri
     * @return string
     */
    public function getAuthorizeUrl($state, array $scope = array(), $redirectUri = null)
    {
        if (empty($scope)) {
            $scope = array('profile_read');
        }

        $redirectUri = $redirectUri ? $redirectUri : $this->getDefaultRedirectUri();

        $url = sprintf('%s://%s%s/oauth/authorize?client_id=%s&response_type=code&scope=%s&redirect_uri=%s&state=%s',
            $this->scheme,
            $this->domain,
            $this->sufix,
            $this->getClientId(),
            implode(' ', $scope),
            $redirectUri,
            $state
        );

        return $url;
    }


    /**
     * @param string $code
     * @param string $redirectUri
     * @return AccessData
     */
    public function getAccessData($code, $redirectUri = null)
    {
        $redirectUri = $redirectUri ? $redirectUri : $this->getDefaultRedirectUri();

        $url = sprintf('%s://%s%s/api/v1/token/get', $this->scheme, $this->domain, $this->sufix);

        if ($this->logger) {
            $this->logger->info('Appsco.AccountsClient.getAccessData', array(
                'url' => $url,
                'code' => $code,
                'clientId' => $this->getClientId(),
                'client_secret' => $this->getClientSecret(),
                'redirect_uri' => $redirectUri,
                'accessToken' => $this->accessToken,
            ));
        }
        $old = $this->getAuthType();
        $this->setAuthType(self::AUTH_TYPE_REQUEST);
        $json = $this->makeRequest(
            $url,
            'post',
            [],
            [
                'code' => $code,
                'redirect_uri' => $redirectUri,
            ]
        );
        $this->setAuthType($old);
        if ($this->logger) {
            $this->logger->info('Appsco.AccountsClient.getAccessData', array(
                'result' => $json,
                'statusCode' => $this->httpClient->getStatusCode(),
            ));
        }

        if ($json === false || $this->httpClient->getStatusCode() != HttpStatusCode::OK) {
            throw new HttpException($this->httpClient->getStatusCode(), sprintf("%s\n%s\n%s\n%s",
                $url, $this->accessToken, $this->httpClient->getErrorText(), $json));
        }

        /** @var AccessData $result */
        $result = $this->serializer->deserialize($json, 'Appsco\Accounts\ApiBundle\Model\AccessData', 'json');

        $this->setAccessToken($result->getAccessToken());

        return $result;
    }


    /**
     * @param string $id
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @return Profile
     */
    public function profileRead($id = 'me')
    {
        $url = sprintf('%s://%s%s/api/v1/profile/%s', $this->scheme, $this->domain, $this->sufix, $id);

        if ($this->logger) {
            $this->logger->info('Appsco.AccountsClient.profileRead', array(
                'id' => $id,
                'url' => $url,
                'accessToken' => $this->accessToken,
            ));
        }

        $json = $this->makeRequest($url);

        if ($this->logger) {
            $this->logger->info('Appsco.AccountsClient.profileRead', array(
                'result' => $json,
                'statusCode' => $this->httpClient->getStatusCode(),
            ));
        }

        if ($json === false || $this->httpClient->getStatusCode() != HttpStatusCode::OK) {
            throw new HttpException($this->httpClient->getStatusCode(), sprintf("%s\n%s\n%s\n%s",
                $url, $this->accessToken, $this->httpClient->getErrorText(), $json));
        }

        return $this->serializer->deserialize($json, 'Appsco\Accounts\ApiBundle\Model\Profile', 'json');
    }

    /**
     * @return User[]
     */
    public function listUsers()
    {
        $json = $this->makeRequest(
            sprintf('%s://%s%s/api/v1/user/list', $this->scheme, $this->domain, $this->sufix)
        );

        return $this->serializer->deserialize($json, "array<Appsco\Accounts\ApiBundle\Model\User>", 'json');
    }

    /**
     * @param $clientId
     * @return CertificateList
     */
    public function certificateGet($clientId)
    {
        $url = sprintf('%s://%s%s/api/v1/certificate/%s', $this->scheme, $this->domain, $this->sufix, $clientId);

        if ($this->logger) {
            $this->logger->info('Appsco.AccountsClient.certificateGet', array(
                'clientId' => $clientId,
                'url' => $url,
                'myId' => $this->getClientId(),
                'mySecret' => $this->getClientSecret(),
            ));
        }

        $json = $this->httpClient->makeRequest(
            $url,
            'get'
        );

        return $this->serializer->deserialize($json, 'Appsco\Accounts\ApiBundle\Model\CertificateList', 'json');
    }

    /**
     * @param string $url
     * @param string $method
     * @param array $queryData
     * @param array $postData
     * @param null $contentType
     * @param array $arrHeaders
     * @return string
     * @throws LogicException
     * @throws HttpException
     */
    protected function makeRequest(
        $url,
        $method = 'post',
        array $queryData = array(),
        array $postData = array(),
        $contentType = null,
        array $arrHeaders = array()
    )
    {
        $this->prepareRequest($arrHeaders, $postData);
        switch($method){
            case 'post':
                $json = $this->httpClient->post($url, $queryData, $postData, $contentType, $arrHeaders);
                break;
            case 'get':
                $json = $this->httpClient->get($url, $queryData, $arrHeaders);
                break;
            case 'delete':
                $json = $this->httpClient->delete($url, $queryData, $arrHeaders);
                break;
            default:
                throw new LogicException("Method is not supported [{$method}]");
        }

        if ($this->httpClient->getStatusCode() != HttpStatusCode::OK) {
            throw new HttpException($this->httpClient->getStatusCode(), $json);
        }

        return $json;
    }

    /**
     * @param $arrHeaders
     * @param $postData
     * @throws RuntimeException
     * @throws LogicException
     */
    private function prepareRequest(&$arrHeaders, &$postData)
    {
        switch($this->authType)
        {
            case self::AUTH_TYPE_ACCESS_TOKEN:
                if(!$this->accessToken){
                    throw new RuntimeException('Access Token must be set');
                }
                $arrHeaders[] = 'Authorization: token '.$this->accessToken;
                break;
            case self::AUTH_TYPE_BASIC_AUTH:
                if(!$this->clientId || !$this->clientSecret){
                    throw new RuntimeException('ClientId and ClientSecret Must be set');
                }
                $this->httpClient->setCredentials($this->getClientId(), $this->getClientSecret());
                break;
            case self::AUTH_TYPE_REQUEST:
                if(!$this->clientId || !$this->clientSecret){
                    throw new RuntimeException('ClientId and ClientSecret Must be set');
                }
                $postData['client_id'] = $this->getClientId();
                $postData['client_secret'] = $this->getClientSecret();
                break;
            default:
                throw new LogicException("Auth Type not supported [{$this->authType}]!");
        }
    }

}