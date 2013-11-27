<?php

namespace Kumatch\Test\KeyStore;

use Kumatch\KeyStore\Storage;
use Kumatch\Path;

class StorageRemoveTest extends StorageTestCase
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
    public function remove($key)
    {
        $driverKey = preg_replace(array('!^/!', '!/$!'), '', $key);

        $driver = $this->createAccessDriver();
        $driver->expects($this->once())
            ->method('remove')
            ->with(
                $this->equalTo($driverKey)
            )
            ->will($this->returnValue(true));

        $storage = new Storage($driver);
        $storage->remove($key);
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
            ->method('remove');

        $storage = new Storage($driver);
        $storage->remove($key);
    }
}