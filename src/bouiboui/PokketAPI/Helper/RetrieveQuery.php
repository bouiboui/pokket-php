<?php

namespace bouiboui\PokketAPI\Helper;

/**
 * Class RetrieveQuery
 * @package bouiboui\PokketAPI\Helper
 * @url https://getpocket.com/developer/docs/v3/retrieve
 * @method RetrieveQuery withContentType(string $contentType) ::CONTENT_TYPE_*
 * @method RetrieveQuery withCount(int $count) Only return $count number of items
 * @method RetrieveQuery withDetailType(string $detailType) ::DETAIL_TYPE_*
 * @method RetrieveQuery withDomain(string $domain) Only return items from a particular $domain
 * @method RetrieveQuery withFavorite(int $zeroOrOne) ::FAVORITE_*
 * @method RetrieveQuery withOffset(int $offset) Used only with count, start returning from $offset position of results
 * @method RetrieveQuery withSearch(string $search) Only return items whose title or url contain the $search string
 * @method RetrieveQuery withSince(string $timestamp) Only return items modified since the given since unix $timestamp
 * @method RetrieveQuery withSort(string $sort) ::SORT_*
 * @method RetrieveQuery withState(string $state) ::STATE_*
 * @method RetrieveQuery withTag(string $tag) ::TAG_*
 */
class RetrieveQuery
{
    const CONTENT_TYPE_ARTICLE = 'article';
    const CONTENT_TYPE_IMAGE = 'image';
    const CONTENT_TYPE_VIDEO = 'video';

    const DETAIL_TYPE_SIMPLE = 'simple';
    const DETAIL_TYPE_COMPLETE = 'complete';

    const FAVORITE_ONLY = 1;
    const FAVORITE_LESS = 0;

    const SORT_NEWEST = 'newest';
    const SORT_OLDEST = 'oldest';
    const SORT_SITE = 'site';
    const SORT_TITLE = 'title';

    const STATE_ALL = 'all';
    const STATE_ARCHIVE = 'archive';
    const STATE_UNREAD = 'unread';

    const TAG_UNTAGGED = '_untagged_';

    /** Builder pattern = private constructor */
    private function __construct()
    {
    }

    /**
     * Builder pattern
     * @return RetrieveQuery
     */
    public static function build()
    {
        return new RetrieveQuery();
    }

    /**
     * Magic variable setter
     * @param $name
     * @param $arguments
     * @return $this
     */
    function __call($name, $arguments)
    {
        $privateVarName = lcfirst(substr($name, strlen('with')));
        $this->{$privateVarName} = $arguments[0];
        return $this;
    }

    /**
     * Array of items to be included in the Retrieve query
     * @return array
     */
    public function toArray()
    {
        return \get_object_vars($this);
    }

}