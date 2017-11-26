<?php

namespace Mic2100\Cache\Suppliers;

use Mic2100\Cache\Exceptions\NotFoundKeyException;

/**
 * Class FileSupplier
 *
 * If you only need to store simple data types such as strings or integers it is recommended to use JSON.
 * This method is much quicker than serialize/unserialize but doesn't work so well if you are storing complex objects.
 *
 * To use JSON just pass true to the second parameter of the constructor
 *
 * @package Mic2100\Cache\Suppliers
 */
class FileSupplier implements SupplierInterface
{
    /**
     * @var array
     */
    private $cache = [];

    /**
     * @var string
     */
    private $filename = '';

    /**
     * @var bool
     */
    private $useJson;

    /**
     * @param string $filename
     * @param bool $useJson
     */
    public function __construct(string $filename, bool $useJson = false)
    {
        $this->filename = $filename;
        $this->useJson = $useJson;
    }

    /**
     * @param string $key
     * @return mixed
     *
     * @throws NotFoundKeyException - If the key does not exist in the cache
     */
    public function get(string $key)
    {
        $this->loadFile();
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
        $this->loadFile();
        $this->cache[$key] = ['value' => $value, 'expires' => time() + $ttl];
        $this->saveFile();

        return true;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function delete(string $key): bool
    {
        $this->loadFile();
        if (isset($this->cache[$key])) {
            unset($this->cache[$key]);
            $this->saveFile();
        }

        return true;
    }

    /**
     * @return bool
     */
    public function deleteAll() : bool
    {
        $this->loadFile();
        $this->cache = [];
        $this->saveFile();

        return true;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function isHit(string $key): bool
    {
        $this->loadFile();

        return isset($this->cache[$key]) && $this->cache[$key]['expires'] > time();
    }

    /**
     * @param string $key
     * @throws NotFoundKeyException
     */
    private function keyExists(string $key)
    {
        if (!isset($this->cache[$key]) || $this->cache[$key]['expires'] <= time()) {
            if (isset($this->cache[$key])) {
                unset($this->cache[$key]);
            }
            $this->saveFile();
            throw new NotFoundKeyException('Unable to find key: ' . $key);
        }
    }

    /**
     * Loads the cache data from the file
     */
    private function loadFile()
    {
        if (file_exists($this->filename) && is_readable($this->filename)) {
            if ($this->useJson) {
                $this->cache = json_decode(file_get_contents($this->filename), true);
            } else {
                $this->cache = unserialize(file_get_contents($this->filename));
            }
        }
    }

    /**
     * Saves the cache array in the file
     */
    private function saveFile()
    {
        if (!file_exists($this->filename)) {
            touch($this->filename);
        }

        is_writable($this->filename) && file_put_contents(
            $this->filename,
            $this->useJson ? json_encode($this->cache) : serialize($this->cache)
        );
    }
}
