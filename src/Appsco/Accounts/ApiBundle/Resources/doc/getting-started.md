Appsco Accounts API Bundle Getting started
==========================================

Prerequisites
-------------

This version of bundle requires Symfony 2.3+

Installation
------------

Easy 6-7 steps:
 * Download appsco/accounts-api with composer
 * Load the bundle to kernel
 * Acquire your client_id and client_secret
 * Configure the AppscoAccountsApiBundle bundle
 * OAuth todo
 * ...


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


Step 4: Configure the AppscoAccountsApiBundle bundle
----------------------------------------------------

Now you have to tell to the Bundle your client_id, client_secret, and redirect uri:

``` yaml
# app/config/config.yml
appsco_accounts_api:
    default_redirect_uri: https://myapp.com/appsco-callback
    client_id: 376i4gytwe0w0wcc84s4ko8o4o0o4ososkk0sskwskc8o4ssgo
    client_secret: 1gexsuuljv1ck0w40g048o8kc080so8ocgw8scsg404o4og4co18ctqsp1770kgc8g48840k8wwk04wccgcskwko40gookgccsgw
```

By default the bundle will use https://accounts.dev.appsco.com. For details check
the [configuration reference](configuration.md)


You are now reay to use Appsco Accounts API Bundle.


Step 5: OAuth & user identity
-----------------------------

To get the Appsco identity of the visitor redirect him to Accounts authorization url, and receive
the authorization grant and make request for access token.

``` php
<?php

namespace AcmeBundle;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function startAction()
    {
        return $this->getAppscoOAuth()->start();
    }

    public function callbackAction(Request $request)
    {
        $token = $this->getAppscoOAuth()->callback($request);
        return new Response("Hello {$token->getUser()->getEmail}!");
    }

    /**
     * @return \Appsco\Accounts\ApiBundle\OAuth\AppscoOAuth
     */
    private function getAppscoOAuth()
    {
        return $this->get('appsco_accounts_api.oauth');
    }

}

```

Next steps
----------

 * [OAuth v2 and OpenID Connect](oauth.md)
 * [Appsco Accounts API](api.md)
 * [Authentication types](authentication.md)

