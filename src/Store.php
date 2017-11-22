<?php

namespace Mic2100\Cache;

use Mic2100\Cache\Exceptions\InvalidArgumentException;
use Mic2100\Cache\Exceptions\NotFoundKeyException;
use Psr\SimpleCache\CacheInterface;

class Store implements CacheInterface
{
    /**
     * @var Configuration
     */
    private $config;

    /**
     * Store constructor.
     *
     * @param Configuration $config
     */
    public function __construct(Configuration $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $this->validateKey($key);

        try {
            return $this->config->getSupplier()->get($key);
        } catch (NotFoundKeyException $exception) {
            return $default;
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param int|null|\DateInterval $ttl
     * @return bool
     */
    public function set($key, $value, $ttl = null) : bool
    {
        $this->validateKey($key);

        return $this->config->getSupplier()->set($key, $value, $ttl);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function delete($key) : bool
    {
        $this->validateKey($key);

        return $this->config->getSupplier()->delete($key);
    }

    /**
     * @return bool
     */
    public function clear() : bool
    {
        return $this->config->getSupplier()->deleteAll();
    }

    /**
     * @param iterable $keys
     * @param mixed|null $default
     * @return \ArrayIterator
     */
    public function getMultiple($keys, $default = null) : \ArrayIterator
    {
        foreach ($keys as $key) {
            $this->validateKey($key);
        }

        $values = new \ArrayIterator;
        foreach ($keys as $key) {
            $values[$key] = $this->get($key, $default);
        }
        
        return $values;
    }

    /**
     * @param iterable $values
     * @param int|null|\DateInterval $ttl
     * @return bool
     */
    public function setMultiple($values, $ttl = null) : bool
    {
        foreach ($values as $key => $value) {
            $this->validateKey($key);
        }

        foreach ($values as $key => $value) {
            
            if (!$this->set($key, $value, $ttl)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param iterable $keys
     * @return bool
     */
    public function deleteMultiple($keys) : bool
    {
        foreach ($keys as $key) {
            $this->validateKey($key);
        }

        foreach ($keys as $key) {
            if (!$this->delete($key)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has($key) : bool
    {
        $this->validateKey($key);

        return $this->config->getSupplier()->isHit($key);
    }

    /**
     * Validates that the key matches the required format /^[A-Za-z0-9\_\.]{1,64}$/
     *
     * @param string $key
     * @return void
     * @throws InvalidArgumentException - if the key does not match the required format
     */
    private function validateKey(string $key)
    {
        if (!preg_match($this->config->getKeyFormat(), $key)) {
            throw new InvalidArgumentException(
                'The key does not match the required format `' . $this->config->getKeyFormat() . '`: ' . $key
            );
        }
    }
}
