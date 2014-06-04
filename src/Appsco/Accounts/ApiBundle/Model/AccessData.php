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


} 