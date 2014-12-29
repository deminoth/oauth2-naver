oauth2-naver
============

## Naver provider for league/oauth2-client

To install, use composer:

```bash
composer require deminoth/oauth2-naver
```

Usage is the same as the league's OAuth client, using `\Deminoth\OAuth2\Client\Provider\Naver` as the provider.
For example:

```php
$provider = new \Deminoth\OAuth2\Client\Provider\Naver([
    'clientId' => "YOUR_CLIENT_ID",
    'clientSecret' => "YOUR_CLIENT_SECRET",
    'redirectUri' => "http://your-redirect-uri"
]);


if (isset($_GET['code']) && $_GET['code']) {
    $token = $this->provider->getAccessToken("authorizaton_code", [
        'code' => $_GET['code']
    ]);

    $user = $this->provider->getUserDetails($token);

    // $user->uid = [ encoded Naver ID ]
    // $user->nickname = [ Naver nickname ]
    // $user->imageUrl = [ Profile Image ]
    // $user->email = [ ID@naver.com ]
}
```