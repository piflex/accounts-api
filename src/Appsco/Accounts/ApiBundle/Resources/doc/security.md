Appsco Account API Bundle Security
==================================

The Appsco Accounts API Bundle implements Symfony 2 security authentication listener and simplifies login with Appsco
process. In order to activate it you should add the `appsco` key in your firewall configuration. For example

``` yaml
# app/config/security.yml
security:
    # ...
    firewalls:
        secured_area:
            # ...
            appsco: true
    # ...
```

It implements an entry point that redirects to the value `oauth_start_path` config option which has default
value `/appsco/start`. The authentication listener will intercept that request and make a redirect response
to Appsco Accounts OAuth authorization page using your Client ID and default redirect url.

The default redirect url should match the value of your `oauth_callback_path` config option which has a default
value `/appsco/callback`. Appsco Accounts will redirect users back to that url upon completed OAuth authorization.

The authentication listener will intercept also that callback request, and retrieve OAuth access token from
Appsco Accounts using Appsco OAuth Client, and authenticate the token with the user provider you defined.

In case you can not find an existing user in your user storage you should throw `UsernameNotFoundException` so
the authentication provider will call the `create` method of your user provider so you can create that new user.

Authenticated security token will be an instance of `AppscoToken` and from it you can get full Appsco Account Profile
object, as well as OAuth access token and OpenID Connect id token.

For detailed configuration reference look at [Security Configuration](configuration.md#security-configuration). You
probably would like to define the `failure_path` option where users will be redirected in case of a login failure.
