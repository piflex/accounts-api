<?php

namespace Appsco\Accounts\ApiBundle\Model;

use JMS\Serializer\Annotation as JMS;

class AccessData
{
    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $accessToken;

    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $scope;

    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $tokenType;

    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $idToken;



    /**
     * @param string $accessToken
     * @return $this|AccessData
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
     * @param string $scope
     * @return $this|AccessData
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
        return $this;
    }

    /**
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @param string $tokenType
     * @return $this|AccessData
     */
    public function setTokenType($tokenType)
    {
        $this->tokenType = $tokenType;
        return $this;
    }

    /**
     * @return string
     */
    public function getTokenType()
    {
        return $this->tokenType;
    }

    /**
     * @param string $idToken
     * @return $this|AccessData
     */
    public function setIdToken($idToken)
    {
        $this->idToken = $idToken;
        return $this;
    }

    /**
     * @return string
     */
    public function getIdToken()
    {
        return $this->idToken;
    }


} 