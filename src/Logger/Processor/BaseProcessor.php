<?php

namespace Shershon\Base\Logger\Processor;

use Monolog\Processor\ProcessorInterface;

class BaseProcessor implements ProcessorInterface
{
    public function __invoke(array $records)
    {
        $microtime             = microtime(true);
        $arr                   = explode('.', $microtime);
        $message               = explode(':', $records['message'], 2);
        $records['module']     = count($message) > 1 ? $message[0] : '';
        $records['message']    = count($message) > 1 ? $message[1] : $records['message'];
        $records['datetime']   = date('Y-m-d H:i:s', $arr[0]) . sprintf('%-03.3s', $arr[1] ?? '');
        $records['request_id'] = REQUEST_ID;
        $records['use_time']   = round($microtime * 1000 - INDEX_START * 1000, 3);
        return $records;
    }
}