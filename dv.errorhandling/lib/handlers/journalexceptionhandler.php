<?php

namespace Dv\ErrorHandling\Handlers;


use Psr\Log;
use Dv\ErrorHandling\Enums\CommonEnum;
use Dv\ErrorHandling\Loggers\JournalLogger;

class JournalExceptionHandler implements IExceptionHandler
{

    public function __construct()
    {
        $this->logger = new JournalLogger(CommonEnum::MODULE_ID, null,'ERROR_HANDLING');
    }

    /** @var Log\LoggerInterface */
    protected $logger;

    /**
     * @param \Throwable $exception
     * @param int $logType
     */
    public function write($logLevel, $message, $context)
    {
        $this->logger->log($logLevel, $message, $context);
    }


}
