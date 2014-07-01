<?php

namespace Appsco\Accounts\ApiBundle\Model;

use JMS\Serializer\Annotation as JMS;

class User
{
    /**
     * @var int
     * @JMS\Type("integer")
     */
    protected $id;

    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $username;

    /**
     * @var boolean
     * @JMS\Type("boolean")
     */
    protected $enabled;

    /**
     * @var boolean
     * @JMS\Type("boolean")
     */
    protected $locked;

    /**
     * @var boolean
     * @JMS\Type("boolean")
     */
    protected $expired;

    /**
     * @var boolean
     * @JMS\Type("boolean")
     */
    protected $credentials_expired;

    /**
     * @var array
     * @JMS\Type("array")
     */
    protected $roles;

    /**
     * @var Profile
     * @JMS\Type("Appsco\Accounts\ApiBundle\Model\Profile")
     */
    protected $profile;


    /**
     * @param int $id
     * @return $this|Profile
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $username
     * @return $this|User
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param \Appsco\Accounts\ApiBundle\Model\Profile $profile
     * @return $this|User
     */
    public function setProfile($profile)
    {
        $this->profile = $profile;
        return $this;
    }

    /**
     * @return \Appsco\Accounts\ApiBundle\Model\Profile
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * @param boolean $credentials_expired
     * @return $this|User
     */
    public function setCredentialsExpired($credentials_expired)
    {
        $this->credentials_expired = $credentials_expired;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getCredentialsExpired()
    {
        return $this->credentials_expired;
    }

    /**
     * @param boolean $enabled
     * @return $this|User
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param boolean $expired
     * @return $this|User
     */
    public function setExpired($expired)
    {
        $this->expired = $expired;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getExpired()
    {
        return $this->expired;
    }

    /**
     * @param boolean $locked
     * @return $this|User
     */
    public function setLocked($locked)
    {
        $this->locked = $locked;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getLocked()
    {
        return $this->locked;
    }

    /**
     * @param array $roles
     * @return $this|User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }



    public function __toString()
    {
        return $this->getUsername();
    }


} 