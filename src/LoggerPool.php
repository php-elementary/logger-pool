<?php

namespace elementary\logger\pool;

use elementary\core\Singleton\SingletonTrait;
use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class LoggerPool extends AbstractLogger
{
    use SingletonTrait;

    /** @var array */
    protected $levelMap = [
        'debug'    => 100,
        'info'     => 200,
        'notice'   => 250,
        'warning'  => 300,
        'error'    => 400,
        'critical' => 500,
        'alert'    => 550,
        'emergency'=> 600,
    ];

    /** @var array */
    protected $loggers = [];

    /**
     * @param LoggerInterface $logger
     * @param string          $level
     *
     * @return $this
     */
    public function setLogger(LoggerInterface $logger, $level=LogLevel::INFO)
    {
        $this->loggers[] = [
            'logger' => $logger,
            'level'  => $level,
        ];

        return $this;
    }

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
        foreach ($this->loggers as $object) {
            if ($this->checkLevel($level, $object['level'])) {
                /** @var LoggerInterface $logger */
                $logger = $object['logger'];
                $logger->log($level, $message, $context);
            }
        }
    }

    /**
     * @param string $levelSource
     * @param string $levelTarget
     *
     * @return bool
     */
    public function checkLevel($levelSource, $levelTarget)
    {
        return $this->levelMap[$levelSource] >= $this->levelMap[$levelTarget];
    }

}