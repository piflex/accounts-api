<?php

namespace Appsco\Accounts\Api\OAuth;

class AppscoOAuthException extends \RuntimeException
{
    /** @var string */
    protected $error;

    public function __construct($error, $message = "", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->error = $error;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

} 