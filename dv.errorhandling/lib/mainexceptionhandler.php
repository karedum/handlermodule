<?php

namespace Dv\ErrorHandling;


use Bitrix\Main\Context;
use Bitrix\Main\Diag\ExceptionHandlerLog;
use Bitrix\Main\Loader;
use Dv\ErrorHandling\Handlers\HandlerFactory;
use Dv\ErrorHandling\Loggers\LogFormatter;

Loader::includeModule('dv.errorhandling');

/**
 * Class MainExceptionHandler
 *
 * @package Dv\ErrorHandling
 */
class MainExceptionHandler extends ExceptionHandlerLog
{

    public function initialize(array $options)
    {

    }

    /**
     * Основной метод отрабатывающий при exception
     * @param \Throwable $exception
     * @param int $logType
     */
    public function write($exception, $logType)
    {

        $arEnabledHandlers = explode(',', Settings::get('channels'));
        //общие настройки и подготовка данных для передачи

        if (!empty($arEnabledHandlers)) {

            $formatter = new LogFormatter();

            $text = $formatter->format("{exception}{trace} - {post} - {get} - {request_uri} - {server_name} - {app_env} {delimiter}\n", [
                'exception' => $exception,
                'trace' => $exception->getTrace(),
                'post' => Context::getCurrent()->getRequest()->getPostList(),
                'get' => Context::getCurrent()->getRequest()->getQueryList(),
                'request_uri' =>  Context::getCurrent()->getServer()->getRequestUri(),
                'server_name' => Context::getCurrent()->getServer()->getServerName(),
                'app_env' => Context::getCurrent()->getEnvironment()->get('APP_ENV'),
            ]);

            $context = [
                'type' => static::logTypeToString($logType),
                'exception_name' => get_class($exception)
            ];

            $logLevel = static::logTypeToLevel($logType);

            $message = "{date} - {type} - {$text}\n";

            foreach ($arEnabledHandlers as $handlerType) {
                $handler = HandlerFactory::make($handlerType);
                if ($handler) {
                    $handler->write($logLevel, $message, $context);
                }
            }
        }
    }

}
