<?php

namespace Mic2100\Cache\Suppliers;

use Mic2100\Cache\Exceptions\NotFoundKeyException;

class ArraySupplier implements SupplierInterface
{
    /**
     * @var array
     */
    private $cache = [];

    /**
     * @param string $key
     * @return mixed
     *
     * @throws NotFoundKeyException - If the key does not exist in the cache
     */
    public function get(string $key)
    {
        $this->keyExists($key);

        return $this->cache[$key]['value'] ?? null;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param int $ttl
     * @return bool
     */
    public function set(string $key, $value, int $ttl = 0): bool
    {
        $this->cache[$key] = ['value' => $value];

        return true;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function delete(string $key): bool
    {
        if (isset($this->cache[$key])) {
            unset($this->cache[$key]);
        }

        return true;
    }

    /**
     * @return bool
     */
    public function deleteAll() : bool
    {
        $this->cache = [];

        return true;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function isHit(string $key): bool
    {
        return isset($this->cache[$key]);
    }

    /**
     * @param string $key
     * @throws NotFoundKeyException
     */
    private function keyExists(string $key)
    {
        if (!isset($this->cache[$key])) {
            throw new NotFoundKeyException('Unable to find key: ' . $key);
        }
    }
}
