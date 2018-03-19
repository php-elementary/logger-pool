<?php

namespace elementary\logger\tests;

use elementary\core\Singleton\SingletonInterface;
use elementary\logger\pool\LoggerPool;
use PHPUnit\Framework\TestCase;
use Psr\Log\AbstractLogger;
use Psr\Log\NullLogger;

class LoggerPoolTest extends TestCase
{
    /**
     * @test
     */
    public function checkInterface()
    {
        $this->assertInstanceOf(SingletonInterface::class, new LoggerPool());
    }

    /**
     * @test
     */
    public function checkLevel()
    {
        $pool = new LoggerPool();

        $this->assertTrue($pool->checkLevel('info', 'debug'));
        $this->assertTrue($pool->checkLevel('info', 'info'));
        $this->assertFalse($pool->checkLevel('info', 'error'));
    }

    /**
     * @test
     */
    public function checkLoggers()
    {
        $pool = new LoggerPool();
        $pool->setLogger(new NullLogger());
        $pool->setLogger(new NullLogger());

        $this->assertEquals(2, count($pool->getLoggers()));
    }

    /**
     * @test
     */
    public function log()
    {
        $infoLogger  = new LoggerForTest();
        $errorLogger = new LoggerForTest();

        $pool = new LoggerPool();
        $pool->setLogger($infoLogger);
        $pool->setLogger($errorLogger, 'error');

        $pool->debug('DebugMessage', ['debug']);
        $pool->info('InfoMessage', ['info']);
        $pool->error('ErrorMessage', ['error']);
        $pool->alert('AlertMessage', ['alert']);

        $this->assertEquals(
            [
                ['info', 'InfoMessage', ['info']],
                ['error', 'ErrorMessage', ['error']],
                ['alert', 'AlertMessage', ['alert']],
            ],
            $infoLogger->getLog()
        );

        $this->assertEquals(
            [
                ['error', 'ErrorMessage', ['error']],
                ['alert', 'AlertMessage', ['alert']],
            ],
            $errorLogger->getLog()
        );
    }
}

class LoggerForTest extends AbstractLogger
{
    /** @var array */
    protected $log = [];

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function log($level, $message, array $context = array())
    {
        $this->log[]= [$level, $message, $context];
    }

    /**
     * @return array
     */
    public function getLog()
    {
        return $this->log;
    }
}