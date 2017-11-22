<?php

namespace Mic2100\Cache\Adapters;

class TestDummy implements CacheAdapterInterface
{
    /**
     * @var array
     */
    private $cache = [];
    
    /**
     * @param string $name
     * @return mixed
     */
    public function get(string $name)
    {
        return $this->cache[$name]['value'] ?? null
    }

    /**
     * @param string $name
     * @param $value
     * @param int $ttl
     * @return bool
     */
    public function set(string $name, $value, int $ttl): bool
    {
        $this->cache[$name] = ['value' => $value, 'ttl' => $ttl];
        
        return true;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function delete(string $name): bool
    {
        isset($this->cache[$name]) && unset($this->cache[$name]);
    }

    /**
     * @return bool
     */
    public function deleteAll()
    {
        $this->cache = [];
    }

    /**
     * @param string $name
     * @return bool
     */
    public function isHit(string $name): bool
    {
        return !is_null($this->get($name));
    }

}