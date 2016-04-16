<?php

session_start();

use bouiboui\PocketAPI\PocketAPI;
use bouiboui\PocketAPI\PocketAPIException;

include_once dirname(__DIR__) . '/vendor/autoload.php';

define('CONSUMER_KEY', '***REMOVED***');
define('REDIRECT_URI', '***REMOVED***/?authorized');

try {

    if (!array_key_exists('pocket.token.request', $_SESSION)) {
        $requestToken = PocketAPI::getRequestToken(CONSUMER_KEY, REDIRECT_URI);
        $_SESSION['pocket.token.request'] = $requestToken;
        header('Location: ' . PocketAPI::REDIRECT_URL . '?request_token=' . $requestToken . '&redirect_uri=' . REDIRECT_URI);
        exit();
    }

    if (!array_key_exists('pocket.token.access', $_SESSION)) {
        $_SESSION['pocket.token.access'] = PocketAPI::getAccessToken(CONSUMER_KEY, $_SESSION['pocket.token.request']);
    }

    PocketAPI::setConsumerKey(CONSUMER_KEY);
    PocketAPI::setAccessToken($_SESSION['pocket.token.access']);

    // make calls
    $posts = PocketAPI::retrieve();

    header('Content-type: application/json;Charset=utf8');
    echo json_encode($posts);

} catch (PocketAPIException $e) {
    echo $e->getMessage();
}