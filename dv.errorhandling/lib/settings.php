<?php

namespace Dv\ErrorHandling;


use Bitrix\Main\Config\Option;
use Dv\ErrorHandling\Enums\CommonEnum;

class Settings
{
    public static $options = [];

    public static function get($name)
    {
        if (empty($options)) {
            self::$options = Option::getForModule(CommonEnum::MODULE_ID);
        }

        return self::$options[$name];
    }

    public static function getAll()
    {
        if (empty($options)) {
            self::$options = Option::getForModule(CommonEnum::MODULE_ID);
        }
        return self::$options;
    }
}