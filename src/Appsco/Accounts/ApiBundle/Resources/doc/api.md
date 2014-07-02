Appsco Accounts API Methods
===========================


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
            "valid_to: "2015-07-01",
            "certificate": "-----BEGIN CERTIFICATE-----\nMIIEZDCCA0ygAwIBAgIBADANB..."
        }
    ]
}
```

