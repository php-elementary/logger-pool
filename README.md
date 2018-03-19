<h1>The pool of different loggers wich impliments \PSR\Log\LoggerInterface</h1>


[![Latest Stable Version](https://poser.pugx.org/elementary/logger-pool/v/stable)](https://packagist.org/packages/elementary/logger-pool)
[![License](https://poser.pugx.org/elementary/logger-pool/license)](https://packagist.org/packages/elementary/logger-pool)
[![Build Status](https://travis-ci.org/php-elementary/logger-pool.svg?branch=master)](https://travis-ci.org/php-elementary/logger-pool)
[![Coverage Status](https://coveralls.io/repos/github/php-elementary/logger-pool/badge.svg)](https://coveralls.io/github/php-elementary/logger-pool)

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/) and then run

```
composer require elementary/logger-pool
```

Usage
-----
```php
use elementary\logger\pool\LoggerPool;
use elementary\logger\traits\LoggerGetInterface;
use elementary\logger\traits\LoggerTrait;
use elementary\logger\graylog\udp\GraylogUdp;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\NullLogger;

class Example implements LoggerGetInterface, LoggerAwareInterface
{
    use LoggerTrait;

    public function doSomeThing()
    {
        // Do some thing
        $this->getLogger()->debug('do some thing');
        
        // Runtime error should be logged and monitored
        $this->getLogger()->error('Attantion! The error was happened!');
    }
}

LoggerPool::me()->setLogger(new NullLogger());
LoggerPool::me()->setLogger(new GraylogUdp('test', 'localhost', 12201), 'warning');

$ex = new Example();
$ex->setLogger(LoggerPool::me());
$ex->doSomeThing();
```

Testing and Code coverage
-------
Unit Tests are located in `tests` directory.
You can run your tests and collect coverage with the following command:
```
vendor/bin/phpunit
```
Result of coverage will be output into the `tests/output` directory.

License
-------
For license information check the [LICENSE](LICENSE)-file.