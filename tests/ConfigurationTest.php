<?php

namespace Mic2100Tests;

use Mic2100\Cache\Configuration;
use Mic2100\Cache\Suppliers\ArraySupplier;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    /**
     * @var Configuration
     */
    private $configuration;

    public function setUp()
    {
        $this->configuration = new Configuration;
    }

    public function testGettersAndSetters()
    {
        $keyFormat = '/^[A-Za-z0-9]{1,90}$/';
        $supplier = new ArraySupplier;
        $this->configuration->setKeyFormat($keyFormat);
        $this->configuration->setSupplier($supplier);

        $this->assertSame($keyFormat, $this->configuration->getKeyFormat());
        $this->assertInstanceOf('Mic2100\Cache\Suppliers\SupplierInterface', $this->configuration->getSupplier());
    }
}
