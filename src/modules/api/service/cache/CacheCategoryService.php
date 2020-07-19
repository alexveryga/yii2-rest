<?php

namespace src\modules\api\service\cache;

/**
 * Class CacheCategoryService.
 */
class CacheCategoryService extends BaseCacheService
{
    const TTL_CATEGORY          = 60 * 60 * 24;
    const CACHE_PREFIX_CATEGORY = 'category_';

    /**
     * CacheCategoryService constructor.
     *
     * @param int   $ttl
     */
    public function __construct(int $ttl = self::TTL_CATEGORY)
    {
        parent::__construct($ttl);
    }

    /**
     * {@inheritDoc}
     */
    public function getPrefix()
    {
        return self::CACHE_PREFIX_CATEGORY;
    }
}
