<?php


namespace bouiboui\PocketAPI;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

/**
 * Class PocketAPI
 * @package bouiboui\PocketAPI
 */
class PocketAPI
{
    const REDIRECT_URL = 'https://getpocket.com/auth/authorize';
    const RETRIEVE_URL = 'https://getpocket.com/v3/get';
    const TOKEN_URL = 'https://getpocket.com/v3/oauth/request';
    const AUTHORIZE_URL = 'https://getpocket.com/v3/oauth/authorize';

    private static $consumerKey;
    private static $client;
    private static $redirectUri;
    private static $accessToken;

    public static function retrieve()
    {
        return self::_post(PocketAPI::RETRIEVE_URL, [
            'state' => 'unread',
            'sort' => 'title',
            'detailType' => 'simple',
            'count' => '100'
        ]);
    }

    private static function _post($url, array $params = [])
    {
        try {

            return json_decode(self::_getClient()->post($url, [
                'json' => array_merge($params, [
                    'consumer_key' => self::$consumerKey,
                    'access_token' => self::$accessToken
                ]),
                'headers' => [
                    'Content-Type' => 'application/json; charset=UTF8',
                    'X-Accept' => 'application/json'
                ]
            ])->getBody(), true);

        } catch (ClientException $e) {
            throw new PocketAPIException($e->getResponse()->getHeader('X-Error')[0]);
        }
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
        try {

            $response = self::_getClient()->post(self::TOKEN_URL, [
                'json' => [
                    'consumer_key' => self::$consumerKey = $consumerKey,
                    'redirect_uri' => self::$redirectUri = $redirectUri
                ],
                'headers' => [
                    'Content-Type' => 'application/json; charset=UTF8',
                    'X-Accept' => 'application/json'
                ]
            ]);

            return json_decode((string)$response->getBody(), true)['code'];

        } catch (ClientException $e) {
            throw new PocketAPIException($e->getResponse()->getHeader('X-Error')[0]);
        }
    }

    public static function setRedirectUri($url)
    {
        self::$redirectUri = $url;
    }

    public static function getAccessToken($consumerKey, $code)
    {
        try {

            $response = self::_getClient()->post(self::AUTHORIZE_URL, [
                'json' => [
                    'consumer_key' => $consumerKey,
                    'code' => $code
                ],
                'headers' => [
                    'Content-Type' => 'application/json; charset=UTF8',
                    'X-Accept' => 'application/json'
                ]
            ]);

            return json_decode((string)$response->getBody(), true)['access_token'];
            //self::$userName = json_decode($response->getBody())['username'];

        } catch (ClientException $e) {
            throw new PocketAPIException($e->getResponse()->getHeader('X-Error')[0]);
        }
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