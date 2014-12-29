<?php

namespace Deminoth\OAuth2\Client\Provider;

use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Service\Client as GuzzleClient;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Entity\User;
use League\OAuth2\Client\Exception\IDPException;

class Naver extends AbstractProvider
{
    
    public function urlAuthorize() 
    {
        return 'https://nid.naver.com/oauth2.0/authorize';
    }

    public function urlAccessToken() 
    {
        return 'https://nid.naver.com/oauth2.0/token';
    }

    public function urlUserDetails(AccessToken $token) 
    {
        return 'https://apis.naver.com/nidlogin/nid/getUserProfile.xml';
    }

    public function getUserDetails(AccessToken $token, $force = false)
    {
        $xml_response = $this->fetchUserDetails($token);

        return $this->userDetails($xml_response, $token);
    }

    public function getUserUid(AccessToken $token, $force = false)
    {
        $xml_response = $this->fetchUserDetails($token, $force);

        return $this->userUid($xml_response, $token);
    }

    public function getUserEmail(AccessToken $token, $force = false)
    {
        $xml_response = $this->fetchUserDetails($token, $force);

        return $this->userEmail($xml_response, $token);
    }

    public function getUserScreenName(AccessToken $token, $force = false)
    {
        $xml_response = $this->fetchUserDetails($token, $force);

        return $this->userScreenName($xml_response, $token);
    }

    public function userDetails($response, AccessToken $token)
    {

        $user = new User;

        $user->uid = (string) $response->response->enc_id;
        $user->nickname = (string) $response->response->nickname;
        $user->name = (string) $response->response->nickname;
        $user->imageUrl = (string) $response->response->profile_image;
        $user->email = (string) $response->response->email;
      
        return $user;

    }

    public function userUid($response, AccessToken $token)
    {
        return (string) $response->response->enc_id;
    }

    public function userEmail($response, AccessToken $token)
    {
        return (string) $response->response->email;
    }

    public function userScreenName($response, AccessToken $token)
    {
        return (string) $response->response->nickname;
    }

    protected function fetchUserDetails(AccessToken $token, $force = true)
    {

        $url = $this->urlUserDetails($token);

        try {

            $client = new GuzzleClient();
            $request = $client->get($url, array('Authorization'=>'Bearer '.$token));
            $response = $request->send();
            $xml_response = $response->xml();

        } catch (BadResponseException $e) {

            $raw_response = explode("\n", $e->getResponse());
            throw new IDPException(end($raw_response));

        }
        
        return $xml_response;
    }
}
