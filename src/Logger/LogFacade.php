<?php

namespace Shershon\Base\Logger;

/**
 * @method static void emergency($message, array $context = array())
 * @method static void alert($message, array $context = array())
 * @method static void critical($message, array $context = array())
 * @method static void error($message, array $context = array())
 * @method static void warning($message, array $context = array())
 * @method static void notice($message, array $context = array())
 * @method static void info($message, array $context = array())
 * @method static void debug($message, array $context = array())
 * @method static void log($message, array $context = array())
 */
class LogFacade
{
    /**
     * instance
     * @var Logger
     */
    private static $instance;

    private function __construct()
    {
    }

    /**
     * Set instance
     *
     * @param Logger $logger
     * @return void
     */
    public static function setInstance(Logger $logger): void
    {
        self::$instance = $logger;
    }

    /**
     * Get instance
     *
     * @return Logger
     */
    public function getInstance(): Logger
    {
        if (is_null(self::$instance)) {
            self::$instance = new Logger();
        }
        return self::$instance;
    }

    public function __call($method, $args): void
    {
        call_user_func_array([self::$instance, $method], $args);
    }

    public static function __callStatic($method, $args): void
    {
        call_user_func_array([self::$instance, $method], $args);
    }
}