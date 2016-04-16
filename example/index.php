<?php

session_start();

use bouiboui\PokketAPI\Helper\RetrieveQuery;
use bouiboui\PokketAPI\PokketAPI;
use bouiboui\PokketAPI\PokketAPIException;

include_once dirname(__DIR__) . '/vendor/autoload.php';

$pokket = new PokketAPI(
    '1234-abcd1234abcd1234abcd1234', // Consumer key
    'https://yourdomain.tld/' // Redirect uri
);

try {

    if (!array_key_exists('pokket.token.request', $_SESSION)) {

        // Redirect to Pocket access request page
        $pokket->requestUserAccess($_SESSION['pokket.token.request'] = $pokket->getRequestToken());

    } else {

        // Request access token
        if (!array_key_exists('pokket.token.access', $_SESSION)) {
            $_SESSION['pokket.token.access'] = $pokket->getAccessToken($_SESSION['pokket.token.request']);
        }
        $pokket->setAccessToken($_SESSION['pokket.token.access']);

        // Retrieve user posts
        $retrieveQuery = RetrieveQuery::build()
            ->withState(RetrieveQuery::STATE_UNREAD)
            ->withSort(RetrieveQuery::SORT_TITLE)
            ->withDetailType(RetrieveQuery::DETAIL_TYPE_SIMPLE)
            ->withCount(100);

        $posts = $pokket->retrieve($retrieveQuery);

        // Display results
        header('Content-type: application/json;Charset=utf8');
        echo json_encode($posts);

    }

} catch (PokketAPIException $e) {

    // Deal with exceptions
    echo $e->getMessage();

}
