<?php

namespace Shershon\Base\Logger\Processor;

use Monolog\Processor\ProcessorInterface;

class OriginProcessor implements ProcessorInterface
{
    public function __invoke(array $records)
    {
        $backTrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $idx       = 0;
        foreach ($backTrace as $index => $trace) {
            if (isset($trace['class']) && $trace['class'] == "Shershon\Base\Logger\LogFacade") {
                $idx = $index + 1;
            }
        }
        if (!empty($backTrace[$idx + 1]['function'])) {
            $records['extra']['func'] = $backTrace[$idx + 1]['function'];
        }
        $records['extra']['file']         = isset($backTrace[$idx + 1]['file']) ? basename($backTrace[$idx + 1]['function']) : '';
        $records['extra']['line']         = isset($backTrace[$idx + 1]['line']) ? basename($backTrace[$idx + 1]['line']) : '';
        $records['extra']['ip']           = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? '';
        $records['extra']['request_url']  = $_SERVER['REQUEST_URI'] ?? '';
        $records['extra']['http_referer'] = $_SERVER['HTTP_REFERER'] ?? '';

        return $records;
    }
}