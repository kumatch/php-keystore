<?php

namespace Kumatch\Test\KeyStore;

use Kumatch\KeyStore\Storage;

class StorageCopyTest extends StorageTestCase
{
    protected $methodName = "copy";

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
        $srcKey = preg_replace(array('!^/!', '!/$!'), '', $key);
        $dstKey = "path/to/destination";

        $driver = $this->createAccessDriver();
        $driver->expects($this->once())
            ->method($this->methodName)
            ->with(
                $this->equalTo($srcKey),
                $this->equalTo($dstKey)
            )
            ->will($this->returnValue(true));

        $parentCount = count( explode('/', $dstKey)) - 1;

        if ($parentCount > 0) {
            $driver->expects($this->exactly($parentCount))
                ->method('exists')
                ->will($this->returnValue(false));
        } else {
            $driver->expects($this->never())
                ->method('exists');
        }

        $storage = new Storage($driver);

        call_user_func_array(array($storage, $this->methodName), array($key, $dstKey));
    }

    /**
     * @test
     * @dataProvider provideKeyForInvalidArgumentException
     * @expectedException \Kumatch\KeyStore\Exception\InvalidArgumentException
     */
    public function throwInvalidArgumentException($key)
    {
        $dstKey = "path/to/destination";

        $driver = $this->createAccessDriver();
        $driver->expects($this->never())
            ->method($this->methodName);

        $storage = new Storage($driver);

        call_user_func_array(array($storage, $this->methodName), array($key, $dstKey));
    }



    /**
     * @test
     * @expectedException \Kumatch\KeyStore\Exception\ParentExistsException
     */
    public function throwExceptionIfDestinationParentKeyIsExists()
    {
        $srcKey = "src";
        $dstKey = "foo/bar/baz/quux";

        $driver = $this->createAccessDriver();
        $driver->expects($this->at(0))
            ->method('exists')
            ->with($this->equalTo("foo"))
            ->will($this->returnValue(false));
        $driver->expects($this->at(1))
            ->method('exists')
            ->with($this->equalTo("foo/bar"))
            ->will($this->returnValue(true));
        $driver->expects($this->never())
            ->method($this->methodName);

        $storage = new Storage($driver);

        call_user_func_array(array($storage, $this->methodName), array($srcKey, $dstKey));
    }

    /**
     * @test
     * @expectedException \Kumatch\KeyStore\Exception\NamespaceExistsException
     */
    public function throwExceptionIfNamespaceKeyIsExists()
    {
        $srcKey = "src";
        $dstKey = "foo/bar/baz/quux";

        $driver = $this->createAccessDriver();
        $driver->expects($this->at(0))
            ->method('exists')
            ->with($this->equalTo("foo"))
            ->will($this->returnValue(false));
        $driver->expects($this->at(1))
            ->method('exists')
            ->with($this->equalTo("foo/bar"))
            ->will($this->returnValue(false));
        $driver->expects($this->at(2))
            ->method('exists')
            ->with($this->equalTo("foo/bar/baz"))
            ->will($this->returnValue(false));
        $driver->expects($this->at(3))
            ->method('isNamespace')
            ->with($this->equalTo("foo/bar/baz/quux"))
            ->will($this->returnValue(true));
        $driver->expects($this->never())
            ->method($this->methodName);

        $storage = new Storage($driver);

        call_user_func_array(array($storage, $this->methodName), array($srcKey, $dstKey));
    }
}