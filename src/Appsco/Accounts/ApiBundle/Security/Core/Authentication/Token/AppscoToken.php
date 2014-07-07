<?php

namespace Appsco\Accounts\ApiBundle\Security\Core\Authentication\Token;

use Appsco\Accounts\ApiBundle\Model\Profile;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class AppscoToken extends AbstractToken
{
    /** @var  Profile */
    protected $profile;

    /** @var  string */
    protected $accessToken;

    /** @var  string */
    protected $idToken;


    /**
     * @param mixed $user
     * @param array $roles
     * @param \Appsco\Accounts\ApiBundle\Model\Profile|null $profile
     * @param string|null $accessToken
     * @param string|null $idToken
     */
    public function __construct($user, array $roles = array(), Profile $profile = null, $accessToken = null, $idToken = null)
    {
        parent::__construct($roles);

        $this->setUser($user);

        $this->profile = $profile;
        $this->accessToken = $accessToken;
        $this->idToken = $idToken;

        parent::setAuthenticated(count($roles) > 0);
    }


    /**
     * {@inheritdoc}
     */
    public function setAuthenticated($isAuthenticated)
    {
        if ($isAuthenticated) {
            throw new \LogicException('Cannot set this token to trusted after instantiation.');
        }

        parent::setAuthenticated(false);
    }

    /**
     * Returns the user credentials.
     *
     * @return mixed The user credentials
     */
    public function getCredentials()
    {
        return '';
    }




    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @return string
     */
    public function getIdToken()
    {
        return $this->idToken;
    }


    /**
     * @return Profile|null
     */
    public function getProfile()
    {
        return $this->profile;
    }




    public function serialize()
    {
        $profileStr = serialize($this->profile);
        $result = serialize(array($profileStr, $this->accessToken, $this->idToken, parent::serialize()));
        return $result;
    }

    public function unserialize($serialized)
    {
        list($profileStr, $this->accessToken, $this->idToken, $parentStr) = unserialize($serialized);
        $this->profile = unserialize($profileStr);
        parent::unserialize($parentStr);
    }


}