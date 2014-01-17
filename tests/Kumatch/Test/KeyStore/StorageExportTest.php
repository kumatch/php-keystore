<?php

namespace Kumatch\Test\KeyStore;

use Kumatch\KeyStore\Storage;
use Kumatch\Fs\Temp\Temp;

class StorageExportTest extends StorageTestCase
{
    protected $exportsFilename;

    protected function setUp()
    {
        parent::setUp();

        $temp = new Temp();
        $dir = $temp->dir()->create();

        $this->exportsFilename = $dir . "/path/to/exports.bin";
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

        $driver = $this->createAccessDriver();
        $driver->expects($this->once())
            ->method('export')
            ->with($this->equalTo($driverKey), $this->equalTo($this->exportsFilename))
            ->will($this->returnValue(true));

        $storage = new Storage($driver);
        $storage->export($key, $this->exportsFilename);
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
            ->method('import');

        $storage = new Storage($driver);

        $storage->import($key, $this->exportsFilename);
    }
}