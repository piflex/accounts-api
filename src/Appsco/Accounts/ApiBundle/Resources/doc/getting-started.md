Appsco Accounts API Bundle Getting started
==========================================

Prerequisites
-------------

This version of bundle requires Symfony 2.3+

Installation
------------

Installation in easy 9 steps:

1. [Download appsco/accounts-api with composer](#step-1-download-appscoaccounts-api-with-composer)
2. [Load the bundle to kernel](#step-2-load-the-bundle-to-kernel)
3. [Acquire your client_id and client_secret](#step-3-acquire-your-client_id-and-client_secret)
4. [Configure the Bundle](#step-4-configure-the-bundle)
5. [User entity](#step-5-user-entity)
6. [User provider](#step-6-user-provider)
7. [Routes](#step-7-routes)
8. [Security configuration](#step-8-security-configuration)
9. [Failure path](#step-9-failure-path)


Step 1: Download appsco/accounts-api with composer
--------------------------------------------------

Add appsco/accounts-api to your ```composer.json``` requirements:

``` json
{
    "require": {
        "appsco/accounts-api": "dev-master"
    }
}
```

Check bundle [releases](https://github.com/Appsco/accounts-api/releases) for the latest stable release.


Step 2: Load the bundle to kernel
---------------------------------

Add AppscoAccountsApiBundle to the kernel of your project:

``` php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Appsco\Accounts\ApiBundle\AppscoAccountsApiBundle(),
    );
}
```

Step 3: Acquire your client_id and client_secret
------------------------------------------------

Register your app at [Appsco Accounts](https://accounts.dev.appsco.com) and copy its client_id and client_secret.

Note that authorization url of the registered app must match the url where from you will be redirecting
users to Appsco Accounts.


Step 4: Configure the bundle
----------------------------

Now you have to tell to the Bundle your client_id, client_secret, and redirect uri:

``` yaml
# app/config/config.yml
appsco_accounts_api:
    default_redirect_uri: https://myapp.com/appsco/callback
    client_id: 376i4gytwe0w0wcc84s4ko8o4o0o4ososkk0sskwskc8o4ssgo
    client_secret: 1gexsuuljv1ck0w40g048o8kc080so8ocgw8scsg404o4og4co18ctqsp1770kgc8g48840k8wwk04wccgcskwko40gookgccsgw
```

By default the bundle will use https://accounts.dev.appsco.com. For details check
the [configuration reference](configuration.md)


You are now ready to use Appsco OAuth but still have to configure the security and user login.


Step 5: User entity
-------------------

You must write your Symfony user class that implements `Symfony\Component\Security\Core\User\UserInterface`. That
comes out of the scopes of this getting started with Appsco Accounts API Bundle, but for this tutorial let's assume
your user entity is

``` php
<?php
namespace Acme\MarketClientBundle\Model;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    // ...
}
```


Step 6: User provider
---------------------

You have to implement `AppscoUserProviderInterface` as user provider. It extends standard Symfony
`UserProviderInterface` and adds one new `create` method.

Writhing an advanced user provider with access to db comes out of the scope of this getting started tutorial, but
it might look like this:


``` php
<?php
namespace Acme\MarketClientBundle\Security\Core\User;

class AppscoUserProvider implements AppscoUserProviderInterface
{
    public function create(AppscoToken $token)
    {
        $result = new User();
        $result
            ->setUsername($token->getProfile()->getEmail())
            ->setRoles(array('ROLE_USER'))
        ;

        // save the user to db

        return $result;
    }

    public function loadUserByUsername($username)
    {
        // load user from db, if not found throw UsernameNotFoundException
    }

    public function refreshUser(UserInterface $user)
    {
        // refreshes the user
    }

    public function supportsClass($class)
    {
        return is_subclass_of($class, 'Acme\MarketClientBundle\Model\User');
    }

}
```

Now you have to register your user provider as a Symfony service

``` yaml
# services.yml
services:
    acme_market_client.user_provider:
        class: Acme\MarketClientBundle\Security\Core\User\AppscoUserProvider
```

Step 7: Routes
--------------

Security listener will intercept requests on OAuth start and callback routes, so you don't have to write any
code in their controller actions, but still have to declare them in routing:

``` yaml
# routing.yml
acme_market_client_appsco_start:
    pattern:  /appsco/start

acme_market_client_appsco_callback:
    pattern:  /appsco/callback
```

Step 8: Security configuration
------------------------------

Now you have to tell Symfony to use your user provider and Appsco authentication listener to do the user login:

``` yaml
# app/config/security.yml
security:
    providers:
        appsco:
            # service id of your user provider that implements AppscoUserProviderInterface
            id: acme_market_client.user_provider

    firewalls:
        secured_area:
            pattern:    ^/
            logout:
                path:   acme_market_client_logout
                target: acme_market_client_homepage
            appsco: true
            anonymous: true

    access_control:
        - { path: ^/secure, roles: ROLE_USER }
        - { path: ^/, roles: IS_AUTHENTICATED_ANONYMOUSLY }

```


Step 9: Failure path
--------------------

In case something went wrong during the OAuth process and authentication exception will be thrown and user
redirected to the failure path configured in security config. You probably want to handle that error on your own,
and to so you should write the failure controller action

``` php
namespace Acme\MarketClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;

class AppscoController extends Controller
{
    public function failureAction(Request $request)
    {
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $request->getSession()->get(SecurityContext::AUTHENTICATION_ERROR);
            $request->getSession()->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render('@AcmeMarketClient/Appsco/failure.html.twig',
            array(
                'error' => $error ? $error->getMessage() : null,
            )
        );
    }
}
```

and the twig view that will display that error message

``` twig
{% extends '::base.html.twig' %}

{% block body %}
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h1>Login Failure</h1>
                {% if error %}
                    <p>{{ error }}</p>
                {% else %}
                    <p>Unknown</p>
                {% endif %}
            </div>
        </div>
    </div>
{% endblock %}

```

and make the route for it

``` yaml
# routing.yml
acme_market_client_appsco_failure:
    pattern:  /appsco/failure
    defaults: { _controller: AcmeMarketClientBundle:Appsco:failure }
```

and specify it route in the security configuration

``` yaml
# app/config/security.yml
security:
    # ...
    firewalls:
        secured_area:
            # ...
            appsco:
                failure_path: acme_market_client_appsco_failure
    # ...
```

Ready to use
------------

Now you are ready to use Appsco Accounts API Bundle. Open your app on `/appsco/start` path to try it. If all went
well, after Appsco Accounts login and authorization page, you should end up on your home page with AppscoToken
in your security context and appropriate user. Or, if something went wrong you'll end up on the login failure
page that will display the error message.


Next steps
----------

 * [OAuth v2 and OpenID Connect](oauth.md)
 * [Appsco Accounts API](api.md)
 * [Authentication types](authentication.md)
 * [Security](security.md)
