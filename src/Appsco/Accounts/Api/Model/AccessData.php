<?php

namespace Appsco\Accounts\Api\Model;

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
     * @return $this|Token
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
     * @return $this|Token
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
     * @return $this|Token
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