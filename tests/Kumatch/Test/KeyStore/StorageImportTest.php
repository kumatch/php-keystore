<?php

namespace Kumatch\Test\KeyStore;

use Kumatch\KeyStore\Storage;

class StorageImportTest extends StorageTestCase
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
    public function runAndSuccess($key)
    {
        $driverKey = preg_replace(array('!^/!', '!/$!'), '', $key);
        $importsFile = $this->binaryFilename;

        $driver = $this->createAccessDriver();
        $driver->expects($this->once())
            ->method('import')
            ->with($this->equalTo($driverKey), $this->equalTo($importsFile))
            ->will($this->returnValue(true));

        $storage = new Storage($driver);
        $storage->import($key, $importsFile);
    }

    /**
     * @test
     * @dataProvider provideKeyForInvalidArgumentException
     * @expectedException \Kumatch\KeyStore\Exception\InvalidArgumentException
     */
    public function throwInvalidArgumentException($key)
    {
        $importsFile = $this->binaryFilename;

        $driver = $this->createAccessDriver();
        $driver->expects($this->never())
            ->method('import');

        $storage = new Storage($driver);

        $storage->import($key, $importsFile);
    }

    /**
     * @test
     * @expectedException \Kumatch\KeyStore\Exception\InvalidArgumentException
     */
    public function throwExceptionIfImportsFileIsNotExists()
    {
        $key = "foo";
        $importsFile = "/path/to/invalid.txt";

        $driver = $this->createAccessDriver();
        $driver->expects($this->never())
            ->method('import');

        $storage = new Storage($driver);

        $storage->import($key, $importsFile);
    }
}