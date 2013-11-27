<?php

namespace Kumatch\Test\KeyStore;

use Kumatch\KeyStore\Storage;
use Kumatch\Fs\Temp\Temp;

class StorageImportExportTest extends StorageTestCase
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
    public function import($key)
    {
        $binaryFilename = $this->binaryFilename;
        $value = $this->loadBinaryFile();

        $storage = $this->getMockBuilder('Kumatch\KeyStore\Storage')
            ->disableOriginalConstructor()
            ->setMethods(array('write'))
            ->getMock();
        $storage->expects($this->once())
            ->method('write')
            ->with($this->equalTo($key), $this->equalTo($value));

        /** @var Storage $storage */
        $storage->import($key, $binaryFilename);
    }

    /**
     * @test
     * @dataProvider provideKeyForSuccess
     */
    public function export($key)
    {
        $temp = new Temp();
        $outputFilename = $temp->file()->create();

        $binaryFilename = $this->binaryFilename;
        $value = $this->loadBinaryFile();

        $this->assertFileNotEquals($binaryFilename, $outputFilename);

        $storage = $this->getMockBuilder('Kumatch\KeyStore\Storage')
            ->disableOriginalConstructor()
            ->setMethods(array('read'))
            ->getMock();
        $storage->expects($this->once())
            ->method('read')
            ->with($this->equalTo($key))
            ->will($this->returnValue($value));

        /** @var Storage $storage */
        $storage->export($key, $outputFilename);

        $this->assertFileEquals($binaryFilename, $outputFilename);
    }
}