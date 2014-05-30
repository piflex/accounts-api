<?php

namespace Appsco\Accounts\Api\OAuth;

final class Scopes
{
    const PROFILE_READ = 'profile_read';
    const PROFILE_WRITE = 'profile_write';
    const APP_READ = 'app_read';
    const APP_WRITE = 'app_write';
    const USERS_READ = 'users_read';
    const USERS_WRITE = 'users_write';

    private function __construct() { }
} 