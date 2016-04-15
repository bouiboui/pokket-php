<?php


namespace bouiboui\PocketAPI;

use GuzzleHttp\Client;

/**
 * Class PocketAPI
 * @package bouiboui\PocketAPI
 */
class PocketAPI
{
    const RETRIEVE_URL = 'https://getpocket.com/v3/get';
    const TOKEN_URL = 'https://getpocket.com/v3/oauth/request';
    const AUTHORIZE_URL = 'https://getpocket.com/v3/oauth/authorize';

    private static $consumerKey;
    private static $client;
    private static $redirectUri;
    private static $accessToken;

    public static function retrieve()
    {
        self::_post(PocketAPI::RETRIEVE_URL);
    }

    private static function _post($url, array $params = [])
    {
        return self::_getClient()->post($url, ['json' => array_merge($params, [
            'consumer_key' => self::$consumerKey,
            'access_token' => self::$accessToken
        ])]);
    }

    private static function _getClient()
    {
        if (null === self::$client) {
            self::$client = new Client();
        }
        return self::$client;
    }

    public static function getRequestToken($consumerKey, $redirectUri)
    {
        $response = self::_getClient()->post(self::TOKEN_URL, [
            'consumer_key' => self::$consumerKey = $consumerKey,
            'redirect_uri' => self::$redirectUri = $redirectUri
        ]);
        if ($response->getStatusCode() !== 200) {
            throw new \ErrorException($response->getHeader('X-Error'));
        }
        return json_decode($response->getBody())['code'];
    }

    public static function setRedirectUri($url)
    {
        self::$redirectUri = $url;
    }

    public static function getAccessToken($consumerKey, $code)
    {
        $response = self::_getClient()->post(self::AUTHORIZE_URL, [
            'json' => [
                'consumer_key' => $consumerKey,
                'code' => $code
            ]
        ]);
        if ($response->getStatusCode() !== 200) {
            throw new \ErrorException($response->getHeader('X-Error'));
        }
        return json_decode($response->getBody())['access_token'];
        //self::$userName = json_decode($response->getBody())['username'];
    }

    public static function setAccessToken($accessToken)
    {
        self::$accessToken = $accessToken;
    }

    public static function setConsumerKey($consumerKey)
    {
        self::$consumerKey = $consumerKey;
    }
}