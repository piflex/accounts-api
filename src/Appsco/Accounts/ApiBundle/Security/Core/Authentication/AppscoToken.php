<?php

namespace Appsco\Accounts\ApiBundle\Security\Core\Authentication;

use Appsco\Accounts\ApiBundle\Model\Profile;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class AppscoToken extends AbstractToken
{
    /** @var  string */
    protected $accessToken;

    /** @var  string */
    protected $idToken;


    /**
     * @param Profile $user
     * @param array $roles
     * @param string|null $accessToken
     * @param string|null $idToken
     */
    public function __construct($user, array $roles = array(), $accessToken = null, $idToken = null)
    {
        parent::__construct($roles);

        $this->setUser($user);

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
        $user = $this->getUser();

        if ($user instanceof Profile) {
            return $user;
        }

        return null;
    }
} 