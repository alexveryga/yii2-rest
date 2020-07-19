<?php

namespace src\modules\api\service\cache;

use yii\caching\CacheInterface;

/**
 * Class BaseCacheService
 *
 * @package src\modules\api\service\cache
 */
abstract class BaseCacheService implements CachableServiceInterface
{
    const TTL_DEFAULT = 1800;

    /**
     * @var CacheInterface
     */
    private CacheInterface $memcache;

    /**
     * @var int
     */
    private int $ttl;

    /**
     * Return cache prefix string.
     *
     * @return string
     */
    public abstract function getPrefix();

    /**
     * CacheArticleService constructor.
     *
     * @param int   $ttl
     */
    public function __construct(int $ttl = self::TTL_DEFAULT)
    {
        $this->memcache = \Yii::$app->cache;
        $this->ttl      = $ttl;
    }

    /**
     * {@inheritDoc}
     */
    public function set($data, int $id, ?int $ttl = null): void
    {
        $this->memcache->set($this->buildCachePrefix($id), $data, $this->calculateTtl($ttl));
    }

    /**
     * {@inheritDoc}
     */
    public function get(int $id)
    {
        return $this->memcache->get($this->buildCachePrefix($id));
    }

    /**
     * Return cache prefix.
     *
     * @param int $id
     *
     * @return string
     */
    protected function buildCachePrefix(int $id)
    {
        return $this->getPrefix() . $id;
    }

    /**
     * @param int|null $ttl
     *
     * @return int
     */
    protected function calculateTtl(?int $ttl = null)
    {
        return $ttl ?? self::TTL_DEFAULT;
    }
}
