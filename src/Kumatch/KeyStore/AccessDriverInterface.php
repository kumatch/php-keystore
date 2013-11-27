<?php
namespace Kumatch\KeyStore;


interface AccessDriverInterface
{
    /**
     * @param $key
     * @return mixed
     */
    public function read($key);

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    public function write($key, $value);

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    public function append($key, $value);

    /**
     * @param $key
     * @return mixed
     */
    public function remove($key);

    /**
     * @param $key
     * @return bool
     */
    public function exists($key);

    /**
     * @param $key
     * @return bool
     */
    public function isNamespace($key);

}