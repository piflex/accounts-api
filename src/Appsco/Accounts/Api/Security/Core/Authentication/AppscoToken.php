<?php

namespace Appsco\Accounts\Api\Security\Core\Authentication;

use Appsco\Accounts\Api\Model\Profile;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class AppscoToken extends AbstractToken
{

    /**
     * @param Profile $user
     * @param array $roles
     */
    public function __construct($user, array $roles = array())
    {
        parent::__construct($roles);

        $this->setUser($user);

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