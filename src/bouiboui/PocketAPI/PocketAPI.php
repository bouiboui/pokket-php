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

    /**
     * PocketAPI constructor.
     * @param string $consumerKey Your consumer key
     * @param string $redirectUri Your redirect URI
     */
    public function __construct($consumerKey, $redirectUri)
    {
        $this->consumerKey = $consumerKey;
        $this->redirectUri = $redirectUri;
    }

    /**
     * Retrieve the logged user's posts
     * @url https://getpocket.com/developer/docs/v3/retrieve
     * @param array $params Parameters - see linked url and this class constants
     * @return array An array containing the results
     * @throws PocketAPIException
     */
    public function retrieve($params = [])
    {
        return $this->_post(self::RETRIEVE_URL,
            array_merge($params, ['access_token' => $this->accessToken])
        );
    }

    /**
     * Used internally to make post requests to the API
     * @param string $url The requested URL
     * @param array $params Parameters to be sent along
     * @return array An array containing the results
     * @throws PocketAPIException
     */
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

    /**
     * Gets or creates an HTTP client for the POST calls (currently Guzzle)
     * @return Client
     */
    private function _getClient()
    {
        return $this->client ?: $this->client = new Client();
    }

    /**
     * Retrieves an Access token for the API calls
     * @param string $code The request code obtained earlier
     * @return string The returned Access token
     * @throws PocketAPIException
     */
    public function getAccessToken($code)
    {
        return $this->_post(self::AUTHORIZE_URL, ['code' => $code])['access_token'];
    }

    /**
     * Sets the internal access token to be used for the API calls
     * @param $accessToken
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * Retrieves a Request token to be converted in an Access token
     * @return string The Request token / code
     * @throws PocketAPIException
     */
    public function getRequestToken()
    {
        return $this->_post(self::TOKEN_URL, ['redirect_uri' => $this->redirectUri])['code'];
    }

    /**
     * Redirects the user to Pocket to continue authorization
     * @param string $requestToken The Request token obtained earlier
     */
    public function requestUserAccess($requestToken)
    {
        header('Location: ' . self::REDIRECT_URL . '/?' . http_build_query([
                'request_token' => $requestToken,
                'redirect_uri' => $this->redirectUri
            ]));
        exit();
    }
}