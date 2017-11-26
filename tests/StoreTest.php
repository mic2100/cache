<?php

namespace Mic2100Tests;

use Mic2100\Cache\Configuration;
use PHPUnit\Framework\TestCase;
use Mic2100\Cache\Store;
use Mic2100\Cache\Suppliers\ArraySupplier;

class StoreTest extends TestCase
{
    /**
     * @var Store
     */
    private $store;

    /**
     * @var array
     */
    private $items = [
        ['a', 1],
        ['b', 1],
        ['c', 2],
        ['d', 3],
        ['e', 5],
        ['f', 8],
        ['g', 13],
        ['h', 21],
        ['i', 34],
        ['j', 55],
        ['k', 89],
    ];

    public function setUp()
    {
        $config = new Configuration();
        $config->setSupplier(new ArraySupplier);

        $this->store = new Store($config);
    }

    /**
     * @dataProvider dataItems
     * @param string $key
     * @param mixed $value
     */
    public function testSet(string $key, $value)
    {
        $this->assertTrue($this->store->set($key, $value, 300));
    }

    /**
     * @dataProvider dataItems
     * @param string $key
     * @param mixed $value
     */
    public function testGet(string $key, $value)
    {
        $this->testSet($key, $value);
        $this->assertSame($value, $this->store->get($key));
    }

    /**
     * @dataProvider dataItems
     * @param string $key
     * @param mixed $value
     */
    public function testDelete(string $key, $value)
    {
        $this->assertTrue($this->store->set($key, $value));
        $this->assertTrue($this->store->delete($key));
        $this->assertFalse($this->store->has($key));
    }

    public function testClear()
    {
        foreach ($this->items as $index => $value) {
            list($key, $data) = $value;
            $this->assertTrue($this->store->set($key, $data, 300));
        }

        //Test that the values were actually set
        foreach ($this->items as $index => $value) {
            list($key, $data) = $value;
            $this->assertSame($data, $this->store->get($key));
        }

        $this->assertTrue($this->store->clear());

        foreach ($this->items as $index => $value) {
            list($key, $data) = $value;
            $this->assertFalse($this->store->has($key));
        }
    }

    public function testGetMultiple()
    {
        $keys = [];
        foreach ($this->items as $index => $value) {
            list($key, $data) = $value;
            $this->assertTrue($this->store->set($key, $data, 300));
            $keys[] = $key;
        }

        $values = $this->store->getMultiple($keys);

        foreach ($this->items as $index => $value) {
            list($key, $data) = $value;
            $this->assertSame($data, $values[$key]);
        }
    }

    public function testGetMultipleGetDefaultValues()
    {
        $keys = [];
        foreach ($this->items as $index => $value) {
            list($key, $data) = $value;
            $this->assertTrue($this->store->set($key, $data, 300));
            $keys[] = $key . '1234';
        }

        $defaultValue = 'The default value!';
        $values = $this->store->getMultiple($keys, $defaultValue);

        foreach ($this->items as $index => $value) {
            list($key, $data) = $value;
            $this->assertSame($defaultValue, $values[$key . '1234']);
        }
    }

    public function testSetMultipleReturnsTrue()
    {
        $values = [];
        foreach ($this->items as $index => $value) {
            list($key, $data) = $value;
            $values[$key] = $data;
        }

        $this->assertTrue($this->store->setMultiple($values));
    }

    public function testDeleteMultipleReturnsTrue()
    {
        $keys = [];
        foreach ($this->items as $index => $value) {
            list($key, $data) = $value;
            $this->assertTrue($this->store->set($key, $data, 300));
            $keys[] = $key;
        }

        $this->assertTrue($this->store->deleteMultiple($keys));

        foreach ($this->items as $index => $value) {
            list($key, $data) = $value;
            $this->assertFalse($this->store->has($key));
        }
    }

    /**
     * @dataProvider dataItems
     * @param string $key
     * @param mixed $value
     */
    public function testHasReturnsTrue(string $key, $value)
    {
        $this->testSet($key, $value);
        $this->assertTrue($this->store->has($key));
    }

    /**
     * @dataProvider dataItems
     * @param string $key
     * @param mixed $value
     */
    public function testHasReturnsFalse(string $key, $value)
    {
        $this->assertFalse($this->store->has($key));
    }

    /**
     * @expectedException \Mic2100\Cache\Exceptions\InvalidArgumentException
     * @expectedExceptionMessage The key does not match the required format `/^[A-Za-z0-9\_\.]{1,64}$/`: <invalid name>
     */
    public function testGetWithInvalidKeyExpectsException()
    {
        $this->store->get('<invalid name>');
    }

    /**
     * @expectedException \Mic2100\Cache\Exceptions\InvalidArgumentException
     * @expectedExceptionMessage The key does not match the required format `/^[A-Za-z0-9\_\.]{1,64}$/`: <invalid name>
     */
    public function testSetWithInvalidKeyExpectsException()
    {
        $this->store->set('<invalid name>', '', 300);
    }

    /**
     * @expectedException \Mic2100\Cache\Exceptions\InvalidArgumentException
     * @expectedExceptionMessage The key does not match the required format `/^[A-Za-z0-9\_\.]{1,64}$/`: <invalid name>
     */
    public function testDeleteWithInvalidKeyExpectsException()
    {
        $this->store->delete('<invalid name>');
    }

    /**
     * @expectedException \Mic2100\Cache\Exceptions\InvalidArgumentException
     * @expectedExceptionMessage The key does not match the required format `/^[A-Za-z0-9\_\.]{1,64}$/`: <invalid name>
     */
    public function testGetMultipleWithInvalidKeyExpectsException()
    {
        $this->store->getMultiple(['<invalid name>']);
    }

    /**
     * @expectedException \Mic2100\Cache\Exceptions\InvalidArgumentException
     * @expectedExceptionMessage The key does not match the required format `/^[A-Za-z0-9\_\.]{1,64}$/`: <invalid name>
     */
    public function testSetMultipleWithInvalidKeyExpectsException()
    {
        $this->store->setMultiple(['<invalid name>' => 1]);
    }

    /**
     * @expectedException \Mic2100\Cache\Exceptions\InvalidArgumentException
     * @expectedExceptionMessage The key does not match the required format `/^[A-Za-z0-9\_\.]{1,64}$/`: <invalid name>
     */
    public function testDeleteMultipleWithInvalidKeyExpectsException()
    {
        $this->store->deleteMultiple(['<invalid name>']);
    }

    /**
     * @expectedException \Mic2100\Cache\Exceptions\InvalidArgumentException
     * @expectedExceptionMessage The key does not match the required format `/^[A-Za-z0-9\_\.]{1,64}$/`: <invalid name>
     */
    public function testHasWithInvalidKeyExpectsException()
    {
        $this->store->has('<invalid name>');
    }

    /**
     * @return array
     */
    public function dataItems() : array
    {
        return $this->items;
    }
}
