Appsco Accounts API Bundle Getting started
==========================================

Prerequisites
-------------

This version of bundle requires Symfony 2.4+

Installation
------------


Step 1: Download appsco/accounts-api with composer
--------------------------------------------------

Add appsco/accounts-api to your ```composer.json``` requirements:

``` json
{
    "require": {
        "aerialship/saml-sp-bundle": "dev-master"
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

Step 3: Acquire your Appsco Accounts client_id and client_secret
----------------------------------------------------------------

Register your app at [Appsco Accounts](https://accounts.dev.appsco.com) and copy its client_id and client_secret


Step 4: Configure the AppscoAccountsApiBundle bundle
----------------------------------------------------

Now you have to tell to the Bundle your client_id and client_secret:

``` yaml
appsco_accounts_api:
    client_id: 376i4gytwe0w0wcc84s4ko8o4o0o4ososkk0sskwskc8o4ssgo
    client_secret: 1gexsuuljv1ck0w40g048o8kc080so8ocgw8scsg404o4og4co18ctqsp1770kgc8g48840k8wwk04wccgcskwko40gookgccsgw
```

By default the bundle will use https://accounts.dev.appsco.com. For details check
the [configuration reference](configuration.md)


Step 5: OAUTH
-------------

TODO

