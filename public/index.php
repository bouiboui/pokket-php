<?php

session_start();

// Log out
if (array_key_exists('logout', $_GET)) {
    // http://php.net/manual/en/function.session-destroy.php#refsect1-function.session-destroy-examples
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    $_SESSION = [];
    session_destroy();

    header('Location: /');
    exit();
}

use bouiboui\PocketAPI\PocketAPI;
use bouiboui\PocketAPI\PocketAPIException;

include_once dirname(__DIR__) . '/vendor/autoload.php';

$pocket = new PocketAPI(
    '***REMOVED***', // Consumer key
    '***REMOVED***/' // Redirect uri
);

try {

    if (!array_key_exists('pocket.token.request', $_SESSION)) {

        // Redirect to Pocket access request page
        $pocket->requestUserAccess($_SESSION['pocket.token.request'] = $pocket->getRequestToken());

    } else {

        // Request access token
        if (!array_key_exists('pocket.token.access', $_SESSION)) {
            $_SESSION['pocket.token.access'] = $pocket->getAccessToken($_SESSION['pocket.token.request']);
        }
        $pocket->setAccessToken($_SESSION['pocket.token.access']);

        // Retrieve user posts
        $posts = $pocket->retrieve([
            'state' => PocketAPI::STATE_UNREAD,
            'sort' => PocketAPI::SORT_TITLE,
            'detailType' => PocketAPI::DETAIL_TYPE_SIMPLE,
            'count' => 100
        ]);

        // Display results
        header('Content-type: application/json;Charset=utf8');
        echo json_encode($posts);

    }

} catch (PocketAPIException $e) {

    // Deal with exceptions
    echo $e->getMessage();

}