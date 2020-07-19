<?php

namespace src\modules\api\service\cache;

/**
 * Interface CachableServiceInterface.
 */
interface CachableServiceInterface
{
    /**
     * Set data to cache.
     *
     * @param mixed    $data
     * @param int      $id
     * @param int|null $ttl
     */
    public function set($data, int $id, ?int $ttl = null): void;

    /**
     * Fetch data from cache.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function get(int $id);
}
