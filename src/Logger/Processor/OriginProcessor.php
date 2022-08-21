<?php

namespace Shershon\Base\Logger\Processor;

use Monolog\Processor\ProcessorInterface;

class OriginProcessor implements ProcessorInterface
{
    public function __invoke(array $record)
    {
        $backTrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $idx       = 0;
        foreach ($backTrace as $index => $trace) {
            if (isset($trace['class']) && $trace['class'] == "Shershon\Base\Logger\LogFacade") {
                $idx = $index + 1;
            }
        }
        if (!empty($backTrace[$idx + 1]['function'])) {
            $record['extra']['func'] = $backTrace[$idx + 1]['function'];
        }
        $record['extra']['file']         = isset($backTrace[$idx + 1]['file']) ? basename($backTrace[$idx + 1]['file']) : '';
        $record['extra']['line']         = isset($backTrace[$idx + 1]['line']) ? basename($backTrace[$idx + 1]['line']) : '';
        $record['extra']['ip']           = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? '';
        $record['extra']['request_url']  = $_SERVER['REQUEST_URI'] ?? '';
        $record['extra']['http_referer'] = $_SERVER['HTTP_REFERER'] ?? '';

        return $record;
    }
}