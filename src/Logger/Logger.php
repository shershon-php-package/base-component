<?php

namespace Shershon\Base\Logger;

use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\AbstractHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as BaseLogger;
use Monolog\Processor\ProcessIdProcessor;
use Psr\Log\LoggerInterface;
use Shershon\Base\Logger\Processor\BaseProcessor;
use Shershon\Base\Logger\Processor\OriginProcessor;

/**
 * @method void emergency($message, array $context = array())
 * @method void alert($message, array $context = array())
 * @method void critical($message, array $context = array())
 * @method void error($message, array $context = array())
 * @method void warning($message, array $context = array())
 * @method void notice($message, array $context = array())
 * @method void info($message, array $context = array())
 * @method void debug($message, array $context = array())
 * @method void log($message, array $context = array())
 */
class Logger
{
    /**
     * Logger instance
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * formatter
     *
     * @var FormatterInterface
     */
    protected $formatter;

    /**
     * handler
     *
     * @var AbstractHandler
     */
    protected $handler;

    /**
     * config
     *
     * @var array
     */
    protected $config = [
        'file'    => null,
        'channel' => 'shershon',
        'level'   => BaseLogger::DEBUG
    ];

    /**
     * Forward call
     *
     * @param $method
     * @param $args
     * @return void
     */
    public function __call($method, $args): void
    {
        call_user_func_array([$this->getLogger(), $method], $args);
    }

    /**
     * Set logger
     *
     * @param LoggerInterface $logger
     * @return $this
     */
    public function setLogger(LoggerInterface $logger): Logger
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * Return the logger instance
     *
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        if (is_null($this->logger)) {
            $this->logger = $this->createLogger();
        }
        return $this->logger;
    }

    /**
     * Make a default log instance
     *
     * @return BaseLogger
     */
    public function createLogger(): BaseLogger
    {
        $handler   = $this->getHandler();
        $formatter = $this->getFormatter();
        $handler->setFormatter($formatter);
        $logger = new BaseLogger($this->config['channel']);
        $logger->pushHandler($handler);
        return $logger;
    }

    /**
     * Set formatter
     *
     * @param FormatterInterface $formatter
     * @return $this
     */
    public function setFormatter(FormatterInterface $formatter): self
    {
        $this->formatter = $formatter;
        return $this;
    }

    /**
     * Get formatter
     *
     * @return FormatterInterface
     */
    public function getFormatter(): FormatterInterface
    {
        if (is_null($this->formatter)) {
            $this->formatter = $this->createFormatter();
        }
        return $this->formatter;
    }

    /**
     * Create formatter
     *
     * @return LineFormatter
     */
    public function createFormatter(): LineFormatter
    {
        return new LineFormatter(
            "[%datetime%] [%level_name%] [%use_time%] [%request_id%] [%module%] [%message%] [%context%] [%extra%]\n",
            null,
            false,
            true
        );
    }

    /**
     * Set handler
     *
     * @param AbstractHandler $handler
     * @return $this
     */
    public function setHandler(AbstractHandler $handler): self
    {
        $this->handler = $handler;
        return $this;
    }

    /**
     * Get handler
     *
     * @return AbstractHandler
     */
    public function getHandler(): AbstractHandler
    {
        if (is_null($this->handler)) {
            $this->handler = $this->createHandler();
        }
        return $this->handler;
    }

    /**
     * Create handler
     *
     * @return AbstractHandler
     */
    public function createHandler(): AbstractHandler
    {
        $file = $this->config['file'] ?? sys_get_temp_dir() . '/logs/' . $this->config['channel'] . '.log';

        $handler = new StreamHandler($file, $this->config['level']);
        $handler->pushProcessor(new ProcessIdProcessor());
        $handler->pushProcessor(new OriginProcessor());
        $handler->pushProcessor(new BaseProcessor());

        return $handler;
    }

    /**
     * Set config
     *
     * @param array $config
     * @return $this
     */
    public function setConfig(array $config): self
    {
        $this->config = $config;
        return $this;
    }

    /**
     * Get config
     *
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }
}