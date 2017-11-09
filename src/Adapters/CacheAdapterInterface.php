<?php

namespace Mic2100\Cache\Adapters;

interface CacheAdapterInterface
{
    public function get(string $name);

    public function set(string $name, $value, int $ttl) : bool;

    public function delete(string $name) : bool;

    public function deleteAll() : bool;

    public function isHit(string $name) : bool;
}