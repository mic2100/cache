<?php

namespace Mic2100Tests\Suppliers;

use Mic2100\Cache\Suppliers\FileSupplier;
use Mic2100\Cache\Suppliers\SupplierInterface;
use PHPUnit\Framework\TestCase;

class FileSupplierTest extends TestCase
{
    const FILENAME = __DIR__ . '/Data/cache.data';

    /**
     * @var SupplierInterface
     */
    private $supplier;

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
        $this->supplier = new FileSupplier(self::FILENAME, true);
    }

    public function tearDown()
    {
        file_put_contents(self::FILENAME, '');
    }

    /**
     * @expectedException \Mic2100\Cache\Exceptions\NotFoundKeyException
     * @expectedExceptionMessage Unable to find key: z
     */
    public function testGetThrowExceptionNoKeyFound()
    {
        $this->supplier->get('z');
    }

    /**
     * @dataProvider dataItems
     * @param string $key
     * @param mixed $value
     */
    public function testSet(string $key, $value)
    {
        $this->assertTrue($this->supplier->set($key, $value, 300));
    }

    /**
     * @dataProvider dataItems
     * @param string $key
     * @param mixed $value
     */
    public function testGet(string $key, $value)
    {
        $this->testSet($key, $value);
        $this->assertSame($value, $this->supplier->get($key));
    }

    /**
     * @dataProvider dataItems
     * @param string $key
     * @param mixed $value
     */
    public function testDelete(string $key, $value)
    {
        $this->assertTrue($this->supplier->set($key, $value));
        $this->assertTrue($this->supplier->delete($key));
        $this->assertFalse($this->supplier->isHit($key));
    }

    public function testDeleteAll()
    {
        foreach ($this->items as $index => $value) {
            list($key, $data) = $value;
            $this->assertTrue($this->supplier->set($key, $data, 300));
        }

        //Test that the values were actually set
        foreach ($this->items as $index => $value) {
            list($key, $data) = $value;
            $this->assertSame($data, $this->supplier->get($key));
        }

        $this->supplier->deleteAll();

        foreach ($this->items as $index => $value) {
            list($key) = $value;
            $this->assertFalse($this->supplier->isHit($key));
        }
    }

    /**
     * @dataProvider dataItems
     * @param string $key
     * @param mixed $values
     */
    public function testIsHit(string $key, $values)
    {
        $this->assertFalse($this->supplier->isHit($key));
        $this->testSet($key, $values);
        $this->assertTrue($this->supplier->isHit($key));
    }

    /**
     * @return array
     */
    public function dataItems() : array
    {
        return $this->items;
    }
}
