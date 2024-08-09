<?php

namespace Dv\ErrorHandling;


use Bitrix\Main\Config\Option;
use Dv\ErrorHandling\Enums\CommonEnum;
use Bitrix\Main\Localization\Loc;

class Validator
{

    public static $errors;

    public static function validate($options)
    {



    }

    public static function getErrors()
    {
        return self::$errors ?? [];
    }


}