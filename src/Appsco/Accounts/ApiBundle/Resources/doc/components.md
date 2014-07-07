Using Appsco Accounts Components w/out Symfony
==============================================

In case you sadly are not on the Symfony2 framework, you still can use classes implemented by the Appsco Accounts
API Bundle individually as components. The Bundle implements factories that handle configuration and create these
components, but definitely you can instantiate them yourself as well.


Accounts Client
---------------

A PHP Accounts Client is implemented in `Appsco\Accounts\ApiBundle\Client\AccountsClient` class and you can
instantiate it yourself and use it to call Appsco Accounts API methods in a convenient way:

``` php
$httpClient = new \BWC\Share\Net\HttpClient\HttpClient();
$jmsSerializer = \JMS\Serializer\SerializerBuilder::create()->build();
$accountsClient = new \Appsco\Accounts\ApiBundle\Client\AccountsClient(
    $httpClient,
    $jmsSerializer,
    'https',
    'accounts.dev.appsco.com',
    '',
    'http://my-site.com/appsco/callback',
    $clientId,
    $clientSecret,
    AccountsClient::AUTH_TYPE_ACCESS_TOKEN,
    null
);
$accountsClient->setAccessToken($accessToken);
$profile = $accountsClient->profileRead('me');
```

OAuth Client
------------

OAuth v2 protocol details related to authorization and access token retrieval are implemented in
`Appsco\Accounts\ApiBundle\OAuth\AppscoOAuth` class.

``` php
$sessionWrapper = new SessionWrapper($_SESSION);
$oauthClient = new \Appsco\Accounts\ApiBundle\OAuth\AppscoOAuth($accountsClient, $sessionWrapper);
$request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
if ($_GET['code']) {
    $token = $oauthClient->callback(
        $request,
        $myRedirectUrl
    );
    print "Hello, ".$token->getProfile()->getEmail();
} else {
    $response = $oauthClient->start(
        array(
            \Appsco\Accounts\ApiBundle\OAuth\Scopes::PROFILE_READ
        ),
        $myRedirectUrl
    );
    header("Location: ".$response->getTargetUrl());
}
```

Though, you would have to write a native PHP $_SESSION wrapper implementation of
`Symfony\Component\HttpFoundation\Session\SessionInterface` since Appsco OAuth Client depends on that interface and
does not use the PHP super global variable $_SESSION directly.

Symfony has a helper method `Request::createFromGlobals()` for request related super global variable
decoupling purposes, so you don't have to write it yourself.

In any case, we're suggesting you re-evaluate your platform choice and reconsider using
[Symfony PHP Framework](http://symfony.com/).

