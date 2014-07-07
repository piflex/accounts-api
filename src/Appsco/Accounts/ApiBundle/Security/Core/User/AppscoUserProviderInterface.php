<?php

namespace Appsco\Accounts\ApiBundle\Security\Core\User;

use Appsco\Accounts\ApiBundle\Security\Core\Authentication\Token\AppscoToken;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

interface AppscoUserProviderInterface extends UserProviderInterface
{
    /**
     * @param \Appsco\Accounts\ApiBundle\Security\Core\Authentication\Token\AppscoToken $token
     * @return UserInterface
     */
    public function create(AppscoToken $token);
} 