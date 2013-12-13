PHP keystore
===========

A simple key-base value storage.

[![Build Status](https://travis-ci.org/kumatch/php-keystore.png?branch=master)](https://travis-ci.org/kumatch/php-keystore)


Install
-----

Add "kumatch/keystore" as a dependency in your project's composer.json file.


    {
      "require": {
        "kumatch/keystore": "*"
      }
    }

And install your dependencies.

    $ composer install


Drivers
----

* [File system](https://github.com/kumatch/php-keystore-filesystem)
* [AWS S3](https://github.com/kumatch/php-keystore-s3)




Methods
----

```php
use Kumatch\KeyStore\Storage;
use Kumatch\KeyStore\Filesystem\Driver;  // driver
```

### __construct ($driver)

Create a storage instance by storage driver.

```php
$driver = new Driver("/tmp");
$storage = new Storage($driver);
```


### write ($key, $value)

```php
$key = "foo/bar";
$value = "Hello, world.";

$storage->write($key, $value);
```


### append ($key, $value)

```php
$key = "foo/bar";
$value = "Hello, world.";

$storage->append($key, $value);
```


### read ($key)

```php
$key = "foo/bar";
$value = $storage->read($key);
// returns a value of a key
// if key is not exists, returns null
```


### exists ($key)

```php
$key = "foo/bar";
$isExists = $storage->exists($key);
// returns boolean
```

### remove ($key)

```php
$key = "foo/bar";
$storage->remove($key);
```

### import ($key, $filename)

```php
$key = "foo/bar";
$filename = "/path/to/input.jpg";

$storage->import($key, $filename);
```

### export ($key, $filename)

```php
$key = "foo/bar";
$filename = "/path/to/output.jpg";

$storage->export($key, $filename);
// outputs a value to file path.
```



License
--------

Licensed under the MIT License.

Copyright (c) 2013 Yosuke Kumakura

Permission is hereby granted, free of charge, to any person
obtaining a copy of this software and associated documentation
files (the "Software"), to deal in the Software without
restriction, including without limitation the rights to use,
copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the
Software is furnished to do so, subject to the following
conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
OTHER DEALINGS IN THE SOFTWARE.
