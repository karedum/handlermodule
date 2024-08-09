<?php

namespace Dv\ErrorHandling\Handlers;



interface IExceptionHandler
{
    public function write($logLevel, $message, $context);
}
