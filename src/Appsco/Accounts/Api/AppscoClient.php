<?php

namespace Appsco\Accounts\Api;

use Appsco\Accounts\Api\Model\AccessData;
use Appsco\Accounts\Api\Model\Profile;
use BWC\Share\Net\HttpClient\HttpClientInterface;
use JMS\Serializer\Serializer;


class AppscoClient
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



    public function __construct(
        HttpClientInterface $httpClient,
        Serializer $serializer,
        $scheme,
        $domain,
        $sufix,
        $defaultRedirectUri,
        $clientId,
        $clientSecret
    ) {
        $this->httpClient = $httpClient;
        $this->serializer = $serializer;
        $this->scheme = $scheme;
        $this->domain = $domain;
        $this->sufix = $sufix;
        $this->defaultRedirectUri = $defaultRedirectUri;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }


    /**
     * @param string $accessToken
     * @return $this|AppscoClient
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
     * @return $this|AppscoClient
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
     * @return $this|AppscoClient
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
     * @return $this|AppscoClient
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

        $json = $this->httpClient->post(
            sprintf('%s://%s%s/api/v1/token/get', $this->scheme, $this->domain, $this->sufix),
            array(),
            array(
                'code' => $code,
                'client_id' => $this->getClientId(),
                'client_secret' => $this->getClientSecret(),
                'redirect_uri' => $redirectUri
            )
        );

        /** @var AccessData $result */
        $result = $this->serializer->deserialize($json, 'Appsco\Accounts\Api\Model\AccessData', 'json');

        $this->setAccessToken($result->getAccessToken());

        return $result;
    }


    /**
     * @param string $id
     * @return Profile
     */
    public function profileRead($id = 'me')
    {
        $json = $this->get(sprintf('%s://%s%s/api/v1/profile/%s', $this->scheme, $this->domain, $this->sufix, $id));

        return $this->serializer->deserialize($json, 'Appsco\Accounts\Api\Model\Profile', 'json');
    }



    /**
     * @param string $url
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

        return $json;
    }


}