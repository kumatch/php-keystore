<?php

namespace Kumatch\Test\KeyStore;

use Kumatch\KeyStore\Storage;
use Kumatch\Path;

class StorageReadTest extends StorageTestCase
{

    protected function setUp()
    {
        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }


    /**
     * @test
     * @dataProvider provideKeyForSuccess
     */
    public function read($key)
    {
        $value = $this->loadBinaryFile();
        $driverKey = preg_replace(array('!^/!', '!/$!'), '', $key);

        $driver = $this->createAccessDriver();
        $driver->expects($this->once())
            ->method('read')
            ->with(
                $this->equalTo($driverKey)
            )
            ->will($this->returnValue($value));

        $storage = new Storage($driver);

        $this->assertEquals($value, $storage->read($key));
    }

    /**
     * @test
     * @dataProvider provideKeyForInvalidArgumentException
     * @expectedException \Kumatch\KeyStore\Exception\InvalidArgumentException
     */
    public function throwInvalidArgumentException($key)
    {
        $driver = $this->createAccessDriver();
        $driver->expects($this->never())
            ->method('read');

        $storage = new Storage($driver);
        $storage->read($key);
    }
}