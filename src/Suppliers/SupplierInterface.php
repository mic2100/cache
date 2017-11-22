<?php

namespace Mic2100\Cache\Suppliers;

interface SupplierInterface
{
    public function get(string $key);

    public function set(string $key, $value, int $ttl = 0) : bool;

    public function delete(string $key) : bool;

    public function deleteAll() : bool;

    public function isHit(string $key) : bool;
}
