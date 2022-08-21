<?php

namespace Shershon\Base\Logger\Processor;

use Monolog\Processor\ProcessorInterface;

class BaseProcessor implements ProcessorInterface
{
    public function __invoke(array $record)
    {
        $microtime            = microtime(true);
        $arr                  = explode('.', $microtime);
        $message              = explode(':', $record['message'], 2);
        $record['module']     = count($message) > 1 ? $message[0] : '';
        $record['message']    = count($message) > 1 ? $message[1] : $record['message'];
        $record['datetime']   = date('Y-m-d H:i:s', $arr[0]) . sprintf('%-03.3s', $arr[1] ?? '');
        $record['request_id'] = REQUEST_ID;
        $record['use_time']   = round($microtime * 1000 - INDEX_START * 1000, 3);
        return $record;
    }
}