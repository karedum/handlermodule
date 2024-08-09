<?php

namespace Dv\ErrorHandling\Agents;


use Dv\ErrorHandling\Enums\CommonEnum;
use Dv\ErrorHandling\Helpers\FileHelper;
use Dv\ErrorHandling\Settings;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\Application;

/**
 * Class FileChecker
 * Агент для удаления старых логов и пустых папок
 * @package Dv\ErrorHandling\Agents
 */
class FileChecker
{

    public static function run()
    {

    }

    public static function add()
    {
        $date = (new DateTime());
        \CAgent::AddAgent(
            "\\Dv\\ErrorHandling\\Agents\\FileChecker::run();",
            CommonEnum::MODULE_ID,
            "N",
            60 * 60,
            "",
            "Y",
            $date->add("-1 days")->toString(),
        );
    }

    public static function remove()
    {
        \CAgent::RemoveAgent(
            "\Dv\ErrorHandling\Agents\FileChecker::run();",
            CommonEnum::MODULE_ID
        );
    }

}