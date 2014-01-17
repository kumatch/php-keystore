<?php

namespace Kumatch\KeyStore;

interface StorageInterface
{

    /**
     * @param string $key
     * @return mixed
     */
    public function read($key);

    /**
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function write($key, $value);

    /**
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function append($key, $value);

    /**
     * @param string $key
     * @return mixed
     */
    public function exists($key);

    /**
     * @param string $key
     * @return mixed
     */
    public function remove($key);

    /**
     * @param string $key
     * @param string $filename
     * @return mixed
     */
    public function import($key, $filename);

    /**
     * @param string $key
     * @param string $filename
     * @return mixed
     */
    public function export($key, $filename);

    /**
     * @param string $srcKey
     * @param string $dstKey
     * @return mixed
     */
    public function copy($srcKey, $dstKey);

    /**
     * @param string $srcKey
     * @param string $dstKey
     * @return mixed
     */
    public function rename($srcKey, $dstKey);
}