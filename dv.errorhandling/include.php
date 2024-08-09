<?php
defined('B_PROLOG_INCLUDED') || die;
CModule::AddAutoloadClasses(
    'dv.errorhandling',
    [
        '\Dv\ErrorHandling\Handlers\IExceptionHandler' => 'lib/handlers/Iexceptionhandler.php'
    ]
);