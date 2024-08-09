<?php

namespace Dv\ErrorHandling\Handlers;


use Bitrix\Main\Application;
use Psr\Log;
use Dv\ErrorHandling\Enums\CommonEnum;
use Dv\ErrorHandling\Loggers\FileLogger;
use Dv\ErrorHandling\Settings;

class FileExceptionHandler implements IExceptionHandler
{

    public function __construct()
    {

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
