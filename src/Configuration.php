<?php

namespace Mic2100\Cache;

use Mic2100\Cache\Suppliers\SupplierInterface;

class Configuration
{
    /**
     * @var string
     */
    private $keyFormat = '/^[A-Za-z0-9\_\.]{1,64}$/';

    /**
     * @var SupplierInterface
     */
    private $supplier;

    /**
     * @return string
     */
    public function getKeyFormat(): string
    {
        return $this->keyFormat;
    }

    /**
     * @param string $keyFormat
     */
    public function setKeyFormat(string $keyFormat)
    {
        $this->keyFormat = $keyFormat;
    }

    /**
     * @return SupplierInterface
     */
    public function getSupplier(): SupplierInterface
    {
        return $this->supplier;
    }

    /**
     * @param SupplierInterface $supplier
     */
    public function setSupplier(SupplierInterface $supplier)
    {
        $this->supplier = $supplier;
    }
}
