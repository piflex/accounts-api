<?php

namespace Appsco\Accounts\ApiBundle\Client;

use Appsco\Accounts\ApiBundle\Model\AccessData;
use Appsco\Accounts\ApiBundle\Model\CertificateList;
use Appsco\Accounts\ApiBundle\Model\Profile;
use BWC\Share\Net\HttpClient\HttpClientInterface;
use BWC\Share\Net\HttpStatusCode;
use JMS\Serializer\Serializer;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AccountsClient
{
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


    public function __construct(
        HttpClientInterface $httpClient,
        Serializer $serializer,
        $scheme,
        $domain,
        $sufix,
        $defaultRedirectUri,
        $clientId,
        $clientSecret,
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

        $json = $this->httpClient->post(
            $url,
            array(),
            array(
                'code' => $code,
                'client_id' => $this->getClientId(),
                'client_secret' => $this->getClientSecret(),
                'redirect_uri' => $redirectUri,
            )
        );

        if ($this->logger) {
            $this->logger->info('Appsco.AccountsClient.getAccessData', array(
                'result' => $json,
            ));
        }

        /** @var AccessData $result */
        $result = $this->serializer->deserialize($json, 'Appsco\Accounts\ApiBundle\Model\AccessData', 'json');

        $this->setAccessToken($result->getAccessToken());

        return $result;
    }


    /**
     * @param string $id
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

        $json = $this->get($url);

        if ($this->logger) {
            $this->logger->info('Appsco.AccountsClient.profileRead', array(
                'result' => $json
            ));
        }

        return $this->serializer->deserialize($json, 'Appsco\Accounts\ApiBundle\Model\Profile', 'json');
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

        $json = $this->httpClient->post(
            $url,
            array(),
            array(
                'client_id' => $this->getClientId(),
                'client_secret' => $this->getClientSecret()
            )
        );

        return $this->serializer->deserialize($json, 'Appsco\Accounts\ApiBundle\Model\CertificateList', 'json');
    }


    /**
     * @param string $url
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @return string
     */
    protected function get($url)
    {
        $json = $this->httpClient->get(
            $url,
            array(),
            array(
                sprintf('Host: %s', $this->domain),
                'Content-length: 0',
                'Authorization: token '.$this->accessToken,
            )
        );

        if ($this->httpClient->getStatusCode() != HttpStatusCode::OK) {
            throw new HttpException($this->httpClient->getStatusCode(), sprintf("%s\n%s\n%s", $url, $this->accessToken, $json));
        }

        return $json;
    }

}