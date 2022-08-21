<?php

namespace Shershon\BaseTest;

use PHPUnit\Framework\TestCase;
use Shershon\Base\Logger\LogFacade;
use Shershon\Base\Logger\Logger;

class LoggertTest extends TestCase
{
    public function testLogFacade()
    {
        define('INDEX_START', microtime(true));//基于计算耗时
        define('REQUEST_ID', 'PHP_' . uniqid(gethostname() . '_'));//用于追踪请求链路

        $config = [
            'file' => __DIR__ . '/../logs/shershon.log'
        ];
        $logger = new Logger();
        $logger->setConfig($config);
        LogFacade::setInstance($logger);

        LogFacade::emergency('test', ['msg' => 'This is a emergency.']);
        LogFacade::alert('test', ['msg' => 'This is a alert.']);
        LogFacade::critical('test', ['msg' => 'This is a critical.']);
        LogFacade::error('test', ['msg' => 'This is a error.']);
        LogFacade::warning('test', ['msg' => 'This is a warning.']);
        LogFacade::notice('test', ['msg' => 'This is a notice.']);
        LogFacade::info('test', ['msg' => 'This is a info.']);
        LogFacade::debug('test', ['msg' => 'This is a debug.']);
        LogFacade::log('test', ['msg' => 'This is a log.']);
    }
}