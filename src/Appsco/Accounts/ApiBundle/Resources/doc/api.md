Appsco Accounts API Methods
===========================

The Appsco Accounts API Bundle provides the PHP implementation of the client in the class

    Appsco\Accounts\ApiBundle\Client\AccountsClient

If you loaded the AppscoAccountsApiBundle to your kernel you can get it as a service from the container

``` php
/** @var \Appsco\Accounts\ApiBundle\Client\AccountsClient $client */
$client = $this->get('appsco_accounts_api.client');
```



Profile Read
------------

Returns profile info for specified user.

    GET https://accounts.dev.appsco.com/api/v1/profile/:id

Parameters
 * :id - the Appsco Accounts id of the user, or 'me' as alias to the user that gave the authorization

Response
``` json
{
    "id": 123,
    "email": "john.smith@example.com",
    "first_name": "John",
    "last_name": "Smith",
    "locale": "en",
    "timezone": "Europe/Oslo",
    "gender": "m",
    "country": "NO",
    "province": "Oslo",
    "city": "Oslo",
    "phone": "00123123123",
    "picture_url": "https://accounts.dev.appsco.com/picture/123"
}
```

Response of this API method is implemented by class

    Appsco\Accounts\ApiBundle\Model\Profile

and the client class method is

``` php
    /**
     * @param string $id
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @return Profile
     */
    public function profileRead($id = 'me');
```



User list
---------

**NOTE** The user list method is in beta status and might change w/out prior notice

Returns user and profile info of users from the same user directory.

    GET https://accounts.dev.appsco.com/api/v1/user/list

Response

``` json
[
    {
        "id": 123,
        "username": "john.smith@example.com",
        "enabled": true,
        "locked": false,
        "expired": false,
        "credentials_expired": false,
        "roles": ["ROLE_USER"],
        "profile": {
            "id": 123,
            "email": "john.smith@example.com",
            "first_name": "John",
            "last_name": "Smith",
            "locale": "en",
            "timezone": "Europe/Oslo",
            "gender": "m",
            "country": "NO",
            "province": "Oslo",
            "city": "Oslo",
            "phone": "00123123123",
            "picture_url": "https://accounts.dev.appsco.com/picture/123"
        }
    }
]
```

Response of this API method is implemented by class

    Appsco\Accounts\ApiBundle\Model\User

and the client class method is

``` php
    /**
     * @return User[]
     */
    public function listUsers();
```


Certificate get
---------------

Returns X509 certificates for specified application.

    GET https://accounts.dev.appsco.com/api/v1/certificate/:client_id

Parameters:

 * :client_id - the Client ID of the application

Response:

``` json
{
    "client_id": "376i4gytwe0w0wcc84s4ko8o4o0o4ososkk0sskwskc8o4ssgo",
    "owner_id": 123,
    "certificates": [
        {
            "valid_from": "2014-07-02",
            "valid_to": "2015-07-01",
            "certificate": "-----BEGIN CERTIFICATE-----\nMIIEZDCCA0ygAwIBAgIBADANB..."
        }
    ]
}
```

Response of this API method is implemented by class

    Appsco\Accounts\ApiBundle\Model\CertificateList

and the client class method is

``` php
    /**
     * @param string $clientId
     * @return CertificateList
     */
    public function certificateGet($clientId);
```

