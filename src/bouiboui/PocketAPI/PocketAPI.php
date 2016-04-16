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
    const AUTHORIZE_URL = 'https://getpocket.com/v3/oauth/authorize';
    const REDIRECT_URL = 'https://getpocket.com/auth/authorize';
    const RETRIEVE_URL = 'https://getpocket.com/v3/get';
    const TOKEN_URL = 'https://getpocket.com/v3/oauth/request';

    const DETAIL_TYPE_SIMPLE = 'simple';
    const SORT_TITLE = 'title';
    const STATE_UNREAD = 'unread';

    private $accessToken;
    private $client;
    private $consumerKey;
    private $redirectUri;

    public function __construct($consumerKey, $redirectUri)
    {
        $this->consumerKey = $consumerKey;
        $this->redirectUri = $redirectUri;
    }

    public function retrieve($params = [])
    {
        return $this->_post(self::RETRIEVE_URL,
            array_merge($params, ['access_token' => $this->accessToken])
        );
    }

    private function _post($url, array $params = [])
    {
        try {

            return json_decode($this->_getClient()->post($url, [
                'json' => array_merge($params, ['consumer_key' => $this->consumerKey]),
                'headers' => [
                    'Content-Type' => 'application/json; charset=UTF8',
                    'X-Accept' => 'application/json'
                ]
            ])->getBody(), true);

        } catch (ClientException $e) {
            throw new PocketAPIException($e->getResponse()->getHeader('X-Error')[0]);
        }
    }

    private function _getClient()
    {
        return $this->client ?: $this->client = new Client();
    }
    
    public function getAccessToken($code)
    {
        return $this->_post(self::AUTHORIZE_URL, ['code' => $code])['access_token'];
    }

    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    public function getRequestToken()
    {
        return $this->_post(self::TOKEN_URL, ['redirect_uri' => $this->redirectUri])['code'];
    }

    public function requestUserAccess($tokenRequest)
    {
        header('Location: ' . self::REDIRECT_URL . http_build_query([
                'request_token' => $tokenRequest,
                'redirect_uri' => self::REDIRECT_URL
            ]));
        exit();
    }
}