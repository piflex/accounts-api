Appsco Accounts API Bundle Configuration Reference
==================================================

Bundle configuration
--------------------

Minimal configuration

``` yaml
# app/config.yml

appsco_accounts_api:
    # the oauth redirect uri you will be using
    default_redirect_uri: 'http://your-app.com/appsco/callback'
    # your appsco accounts client id
    client_id: ''
    # your appsco accounts client secret
    client_secret: ''
```


Full configuration with default values

``` yaml
# app/config.yml

appsco_accounts_api:
    # appsco accounts you will use
    scheme: https
    domain: accounts.dev.appsco.com

    # data from the application registration
    default_redirect_uri: ''
    client_id: ''
    client_secret: ''

    # path to CA certificates
    ca_path: /usr/lib/ssl/certs

    # set to true to skip ssl certificate check
    loose_ssl: false
```

Security configuration
----------------------


``` yaml
# app/config/security.yml
security:
    firewalls:
        secured_area:
            appsco:
                oauth_start_path: /appsco/start
                oauth_callback_path: /appsco/callback

```