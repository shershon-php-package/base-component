<?php

namespace Shershon\BaseTest;

use PHPUnit\Framework\TestCase;
use Shershon\Base\Logger\LogFacade;
use Shershon\Base\Logger\Logger;

class LoggerTest extends TestCase
{
    public function testLogFacade()
    {
        define('INDEX_START', microtime(true));//基于计算耗时
        define('REQUEST_ID', 'PHP_' . uniqid(gethostname() . '_'));//用于追踪请求链路

        $config = [
            'file' => __DIR__ . '/../../logs/shershon.log'
        ];
        $logger = new Logger();
        $logger->setConfig($config);
        LogFacade::setInstance($logger);

        LogFacade::emergency('test:emergency', ['params' => ['name' => 'zs'], 'result' => []]);
        LogFacade::alert('test:alert', ['params' => ['name' => 'zs'], 'result' => []]);
        LogFacade::critical('test:critical', ['params' => ['name' => 'zs'], 'result' => []]);
        LogFacade::error('test:error', ['params' => ['name' => 'zs'], 'result' => []]);
        LogFacade::warning('test:warning', ['params' => ['name' => 'zs'], 'result' => []]);
        LogFacade::notice('test:notice', ['params' => ['name' => 'zs'], 'result' => []]);
        LogFacade::info('test:info', ['params' => ['name' => 'zs'], 'result' => []]);
        LogFacade::debug('test:debug', ['params' => ['name' => 'zs'], 'result' => []]);
    }
}