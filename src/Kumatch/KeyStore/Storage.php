<?php
namespace Kumatch\KeyStore;

use Kumatch\Path;
use Kumatch\KeyStore\Exception\InvalidArgumentException;
use Kumatch\KeyStore\Exception\ParentExistsException;
use Kumatch\KeyStore\Exception\NamespaceExistsException;

class Storage implements StorageInterface
{
    /** @var  AccessDriverInterface */
    protected $driver;

    public function __construct(AccessDriverInterface $driver)
    {
        $this->driver = $driver;
    }

    /**
     * @return AccessDriverInterface
     */
    public function getDriver()
    {
        return $this->driver;
    }


    /**
     * @param string $key
     * @param mixed $value
     * @throws InvalidArgumentException
     * @return mixed
     */
    public function write($key, $value)
    {
        if (!$key = $this->normalizeKey($key)) {
            throw new InvalidArgumentException('invalid key.');
        }

        $this->prepareWriteKey($key);

        return $this->getDriver()->write($key, $value);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @throws InvalidArgumentException
     * @return mixed
     */
    public function append($key, $value)
    {
        if (!$key = $this->normalizeKey($key)) {
            throw new InvalidArgumentException('invalid key.');
        }

        $this->prepareWriteKey($key);

        return $this->getDriver()->append($key, $value);
    }

    /**
     * @param string $key
     * @throws InvalidArgumentException
     * @return mixed
     */
    public function read($key)
    {
        if (!$key = $this->normalizeKey($key)) {
            throw new InvalidArgumentException('invalid key.');
        }

        return $this->getDriver()->read($key);
    }

    /**
     * @param string $key
     * @throws InvalidArgumentException
     * @return bool|mixed
     */
    public function exists($key)
    {
        if (!$key = $this->normalizeKey($key)) {
            throw new InvalidArgumentException('invalid key.');
        }

        return $this->getDriver()->exists($key) ? true : false;
    }

    /**
     * @param string $key
     * @throws InvalidArgumentException
     * @return mixed
     */
    public function remove($key)
    {
        if (!$key = $this->normalizeKey($key)) {
            throw new InvalidArgumentException('invalid key.');
        }

        return $this->getDriver()->remove($key);
    }

    /**
     * @param string $key
     * @param string $filename
     * @throws InvalidArgumentException
     * @return mixed
     */
    public function import($key, $filename)
    {
        if (!file_exists($filename)) {
            throw new InvalidArgumentException(sprintf('file "%s" is not exists.', $filename));
        }

        return $this->write($key, file_get_contents($filename));
    }

    /**
     * @param string $key
     * @param string $filename
     * @throws InvalidArgumentException
     * @return mixed
     */
    public function export($key, $filename)
    {
        $dirname = Path::dirname($filename);

        if (!file_exists($dirname) && !@mkdir($dirname, 0755, true)) {
            throw new InvalidArgumentException(sprintf('Cannot create directory "%s".', $dirname));
        }

        file_put_contents($filename, $this->read($key));
    }



    /**
     * @param $key
     * @return bool
     * @throws InvalidArgumentException
     */
    protected function normalizeKey($key)
    {
        if (!is_scalar($key)) {
            throw new InvalidArgumentException('invalid key: not scalar.');
        }

        if (is_bool($key)) {
            throw new InvalidArgumentException('invalid key: a boolean.');
        }

        if (is_null($key) || $key === "") {
            throw new InvalidArgumentException('invalid key: a blank.');
        }

        $key = Path::normalize( preg_replace('!^([/]*)([^/].*[^/])([/]*)$!', '\2', $key) );

        if (preg_match('!^[\./]+!', $key)) {
            throw new InvalidArgumentException('invalid key: traverse directory.');
        }

        return $key;
    }


    /**
     * @param string $key
     * @throws NamespaceExistsException
     * @throws ParentExistsException
     * @return bool
     */
    protected function prepareWriteKey($key)
    {
        foreach ($this->listParentKeys($key) as $parent) {
            $parentKey = $this->normalizeKey($parent);

            if ($this->getDriver()->exists($parentKey)) {
                throw new ParentExistsException(sprintf('a key "%s" parent exists.', $key));
            }
        }

        if ($this->getDriver()->isNamespace($key)) {
            throw new NamespaceExistsException(sprintf('a key "%s" parent exists.', $key));
        }

        return true;
    }

    /**
     * @param $key
     * @return array
     */
    protected function listParentKeys($key)
    {
        $keys = explode('/', $key);
        $count = count($keys);

        $current = null;
        $parentKeys = array();

        for ($i = 0; $i < $count - 1; ++$i) {
            if (count($parentKeys)) {
                $current .= "/{$keys[$i]}";
            } else {
                $current = $keys[$i];
            }

            array_push($parentKeys, $current);
        }

        return $parentKeys;
    }
}