<?php

namespace Kumatch\Test\KeyStore;

use Kumatch\KeyStore\Storage;

class StorageWriteTest extends StorageTestCase
{
    protected $methodName = "write";

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
        $value = $this->loadBinaryFile();
        $driverKey = preg_replace(array('!^/!', '!/$!'), '', $key);

        $driver = $this->createAccessDriver();
        $driver->expects($this->once())
            ->method($this->methodName)
            ->with(
                $this->equalTo($driverKey),
                $this->equalTo($value)
            )
            ->will($this->returnValue(true));

        $parentCount = count( explode('/', $driverKey)) - 1;

        if ($parentCount > 0) {
            $driver->expects($this->exactly($parentCount))
                ->method('exists')
                ->will($this->returnValue(false));
        } else {
            $driver->expects($this->never())
                ->method('exists');
        }

        $storage = new Storage($driver);

        call_user_func_array(array($storage, $this->methodName), array($key, $value));
    }

    /**
     * @test
     * @dataProvider provideKeyForInvalidArgumentException
     * @expectedException \Kumatch\KeyStore\Exception\InvalidArgumentException
     */
    public function throwInvalidArgumentException($key)
    {
        $value = "foo";

        $driver = $this->createAccessDriver();
        $driver->expects($this->never())
            ->method($this->methodName);

        $storage = new Storage($driver);

        call_user_func_array(array($storage, $this->methodName), array($key, $value));
    }



    /**
     * @test
     * @expectedException \Kumatch\KeyStore\Exception\ParentExistsException
     */
    public function throwExceptionIfParentKeyExists()
    {
        $key = "foo/bar/baz/quux";
        $value = $this->loadBinaryFile();

        $driver = $this->createAccessDriver();
        $driver->expects($this->at(0))
            ->method('exists')
            ->with($this->equalTo("foo"))
            ->will($this->returnValue(false));
        $driver->expects($this->at(1))
            ->method('existsNamespace')
            ->will($this->returnValue(false));
        $driver->expects($this->at(2))
            ->method('exists')
            ->with($this->equalTo("foo/bar"))
            ->will($this->returnValue(true));
        $driver->expects($this->never())
            ->method($this->methodName);

        $storage = new Storage($driver);

        call_user_func_array(array($storage, $this->methodName), array($key, $value));
    }

    /**
     * @test
     * @expectedException \Kumatch\KeyStore\Exception\NamespaceExistsException
     */
    public function throwExceptionIfNamespaceKeyExists()
    {
        $key = "foo/bar/baz/quux";
        $value = $this->loadBinaryFile();

        $driver = $this->createAccessDriver();
        $driver->expects($this->at(0))
            ->method('exists')
            ->with($this->equalTo("foo"))
            ->will($this->returnValue(false));
        $driver->expects($this->at(1))
            ->method('existsNamespace')
            ->will($this->returnValue(false));
        $driver->expects($this->at(2))
            ->method('exists')
            ->with($this->equalTo("foo/bar"))
            ->will($this->returnValue(false));
        $driver->expects($this->at(3))
            ->method('existsNamespace')
            ->with($this->equalTo("foo/bar"))
            ->will($this->returnValue(true));
        $driver->expects($this->never())
            ->method($this->methodName);

        $storage = new Storage($driver);

        call_user_func_array(array($storage, $this->methodName), array($key, $value));
    }
}