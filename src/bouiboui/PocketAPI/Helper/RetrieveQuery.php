<?php

namespace bouiboui\PocketAPI\Helper;

/**
 * Class RetrieveQuery
 * @package bouiboui\PocketAPI\Helper
 * @url https://getpocket.com/developer/docs/v3/retrieve
 * @method RetrieveQuery withContentType(string $contentType)
 * @method RetrieveQuery withCount(int $count)
 * @method RetrieveQuery withDetailType(string $detailType)
 * @method RetrieveQuery withFavorite(int $zeroOrOne)
 * @method RetrieveQuery withOffset(int $offset)
 * @method RetrieveQuery withSince(string $timestamp)
 * @method RetrieveQuery withSort(string $sort)
 * @method RetrieveQuery withState(string $state)
 * @method RetrieveQuery withTag(string $tag)
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
    public static function create()
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
        $privateVarName = '_' . lcfirst(substr($name, strlen('with')));
        $this->{$privateVarName} = $arguments[0];
        return $this;
    }

}