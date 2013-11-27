<?php

namespace Kumatch\Test\KeyStore;

use Kumatch\KeyStore\Storage;
use Kumatch\Path;

class StorageExistsTest extends StorageTestCase
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
    public function exists($key)
    {
        $result = mt_rand(0, 1);
        $driverKey = preg_replace(array('!^/!', '!/$!'), '', $key);

        $driver = $this->createAccessDriver();
        $driver->expects($this->once())
            ->method('exists')
            ->with(
                $this->equalTo($driverKey)
            )
            ->will($this->returnValue($result));

        $storage = new Storage($driver);

        if ($result) {
            $this->assertTrue($storage->exists($key));
        } else {
            $this->assertFalse($storage->exists($key));
        }
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
            ->method('exists');

        $storage = new Storage($driver);
        $storage->exists($key);
    }
}