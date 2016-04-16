# pokket-php

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/9d637ad2-7c06-4e8c-a2b2-7e3bae3e1ad4/mini.png)](https://insight.sensiolabs.com/projects/9d637ad2-7c06-4e8c-a2b2-7e3bae3e1ad4)

PHP SDK to access the [Pocket](https://getpocket.com) [API](https://getpocket.com/developer/docs/overview) (v3, currently).

*Wait, why don't you call it* Pocket API PHP SDK *or something similar then?*

[It's actually forbidden](https://getpocket.com/developer/docs/branding) because business™. 

I'm a big fan of Pocket and I have some ideas I want to try out thanks to its API. The other librairies I found on Github were either too small, too big, or not documented enough for my needs. [duellsy/pockpack](https://github.com/duellsy/pockpack) seems very nicely done, it's a shame I found it too late.


## Install

``` bash
$ composer require bouiboui/pokket-php
```

## Usage

*Note: For a complete example: [example/index.php](https://github.com/bouiboui/pokket-php/blob/master/example/index.php)*

Initialize Pokket with your [Consumer key](https://getpocket.com/developer/apps/new) and Redirect URI (classic OAuth)

``` php
$pokket = new PokketAPI(
    '1234-abcd1234abcd1234abcd1234', // Consumer key
    'https://yourdomain.tld/' // Redirect uri
);
```

First we ask the user to login

``` php
if (!array_key_exists('pokket.token.request', $_SESSION)) {
    $pokket->requestUserAccess($_SESSION['pokket.token.request'] = $pokket->getRequestToken());
} 
```
Then we fetch an Access token

``` php
if (!array_key_exists('pokket.token.access', $_SESSION)) {
    $_SESSION['pokket.token.access'] = $pokket->getAccessToken($_SESSION['pokket.token.request']);
}
$pokket->setAccessToken($_SESSION['pokket.token.access']);
```

Now we can get down to business™

``` php
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
```

The code above returns (minus the *pretty format*):

```json
{
	"status": 1,
	"complete": 1,
	"list": {
		"2093856": {
			"item_id": "2093856",
			"resolved_id": "2093856",
			"given_url": "http://www.csie.ntu.edu.tw/~cjlin/libsvm/",
			"given_title": "",
			"favorite": "0",
			"status": "0",
			"time_added": "1390842458",
			"time_updated": "1390927561",
			"time_read": "0",
			"time_favorited": "0",
			"sort_id": 74,
			"resolved_title": "LIBSVM -- A Library for Support Vector Machines",
			"resolved_url": "http://www.csie.ntu.edu.tw/~cjlin/libsvm/",
			"excerpt": "LIBSVM -- A Library for Support Vector Machines Chih-Chung Chang and Chih-Jen Lin  Version 3.19 released on October 27, 2014. It conducts some minor fixes.LIBSVM tools provides many extensions of LIBSVM. Please check it if you need some functions not supported in LIBSVM.",
			"is_article": "0",
			"is_index": "1",
			"has_video": "0",
			"has_image": "1",
			"word_count": "961"
		},
		" ... and 99 more elements ... "
	},
	"error": null,
	"search_meta": {
		"search_type": "normal"
	},
	"since": 1460836019
}
```

## Credits

- bouiboui — [Github](https://github.com/bouiboui) [Twitter](https://twitter.com/j_____________n) [Website](http://cod3.net)
- [All contributors](https://github.com/bouiboui/tissue/graphs/contributors)

## License

Unlicense. Public domain, basically. Please treat it kindly. See [License File](LICENSE) for more information. 

This project uses the following open source projects 
- [guzzle/guzzle](https://github.com/guzzle/guzzle) by [Michael Dowling](https://github.com/mtdowling) — [License](https://github.com/guzzle/guzzle/blob/master/LICENSE).


[ico-version]: https://img.shields.io/packagist/v/bouiboui/pokket-php.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-Unlicense-brightgreen.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/bouiboui/pokket-php
[link-author]: https://github.com/bouiboui
