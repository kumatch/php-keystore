<?php

namespace Kumatch\Test\KeyStore;

class StorageTestCase extends \PHPUnit_Framework_TestCase
{
    /** @var  string */
    protected $binaryFilename;

    protected function setUp()
    {
        parent::setUp();

        $this->binaryFilename = __DIR__ . "/sample.png";
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    protected function loadBinaryFile()
    {
        $fh = fopen($this->binaryFilename, "rb");
        $binary = fread($fh, filesize($this->binaryFilename));
        fclose($fh);

        return $binary;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createAccessDriver()
    {
        return $this->getMockBuilder('Kumatch\KeyStore\AccessDriverInterface')
            ->setMethods(array('write', 'append', 'read', 'exists', 'existsNamespace', 'remove'))
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return array
     */
    public function provideKeyForSuccess()
    {
        return array(
            array("foo"),
            array(42),
            array("aaa/bbb"),
            array("/path/to/a.txt"),
            array("path/to/abc/")
        );
    }

    /**
     * @return array
     */
    public function provideKeyForInvalidArgumentException()
    {
        return array(
            array(""),
            array(null),
            array(true),
            array( array("foo") ),
            array( (object)array("foo" => "bar") ),

            array("."),
            array(".."),
            array("foo/bar/../../../"),
            array("../foo/bar"),
            array("/../../foo/bar/baz"),
        );
    }
}