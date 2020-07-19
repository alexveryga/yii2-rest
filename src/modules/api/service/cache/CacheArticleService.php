<?php

namespace src\modules\api\service\cache;

/**
 * Class CacheArticleService.
 */
class CacheArticleService extends BaseCacheService
{
    const TTL_ARTICLE          = 1800;
    const CACHE_PREFIX_ARTICLE = 'article_';

    /**
     * CacheArticleService constructor.
     *
     * @param int   $ttl
     */
    public function __construct(int $ttl = self::TTL_ARTICLE)
    {
        parent::__construct($ttl);
    }

    /**
     * {@inheritDoc}
     */
    public function getPrefix()
    {
        return self::CACHE_PREFIX_ARTICLE;
    }
}
