<?php

namespace Dv\ErrorHandling\Handlers;


use Bitrix\Main\Application;
use Psr\Log;
use Dv\ErrorHandling\Enums\CommonEnum;
use Dv\ErrorHandling\Helpers\EmailHelper;
use Dv\ErrorHandling\Loggers\EmailLogger;
use Dv\ErrorHandling\Model\ExceptionsTable;
use Dv\ErrorHandling\Settings;

class EmailExceptionHandler implements IExceptionHandler
{

    public function __construct()
    {
        $this->logger = new EmailLogger(EmailHelper::getEmails());
    }

    /** @var Log\LoggerInterface */
    protected $logger;

    /**
     * @param \Throwable $exception
     * @param int $logType
     */
    public function write($logLevel, $message, $context)
    {


    }



}
