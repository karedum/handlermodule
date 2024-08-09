<?php

namespace Dv\ErrorHandling\Handlers;



class HandlerFactory
{
    const JOURNAL = 'journal';
    const ELASTICSEARCH = 'elasticsearch';
    const FILES = 'files';
    const EMAIL = 'email';

    const TYPES = [
        self::JOURNAL => JournalExceptionHandler::class,
        self::ELASTICSEARCH => ElasticExceptionHandler::class,
        self::FILES => FileExceptionHandler::class,
      self::EMAIL => EmailExceptionHandler::class,
    ];

    public static function make($type): ?IExceptionHandler
    {
        $class = self::TYPES[$type];
        if (isset($class) && class_exists($class)) {
            return new $class();
        }
        return null;
    }

}
