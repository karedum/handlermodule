<?php

namespace Dv\ErrorHandling\Agents;


use Psr\Log\LogLevel;
use Dv\ErrorHandling\Enums\CommonEnum;
use Dv\ErrorHandling\Helpers\EmailHelper;
use Dv\ErrorHandling\Helpers\FileHelper;
use Dv\ErrorHandling\Loggers\EmailLogger;
use Dv\ErrorHandling\Model\ExceptionsTable;
use Dv\ErrorHandling\Settings;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\Application;

/**
 * Class EmailCheker
 * Агент для отправки писем с ошибками
 * @package Dv\ErrorHandling\Agents
 */
class EmailChecker
{

    public static function run()
    {
        $query = ExceptionsTable::query();
        $query->registerRuntimeField(
            'MESSAGES',
            [
                'data_type' => 'string',
                'expression' => ['GROUP_CONCAT(%s SEPARATOR "\n")', 'MESSAGE']
            ]
        );
        $query->registerRuntimeField(
            'IDS',
            [
                'data_type' => 'string',
                'expression' => ['GROUP_CONCAT(%s SEPARATOR ",")', 'ID']
            ]
        );
        $query->registerRuntimeField(
            'COUNT',
            [
                'data_type' => 'string',
                'expression' => ['COUNT(%s)', 'ID']
            ]
        );
        $query->setSelect([
            'EXCEPTION_NAME', 'MESSAGES', 'IDS', 'COUNT', 'LOG_LEVEL'
        ]);
        $query->setGroup(['EXCEPTION_NAME']);

        $res = $query->exec();
        $logger = new EmailLogger(EmailHelper::getEmails());
        while ($errors = $res->fetch()) {
            $logger->log($errors['LOG_LEVEL'], $errors['MESSAGES'], [
                'COUNT' => $errors['COUNT']
            ]);
            $ids = explode(',', $errors['IDS']);
            ExceptionsTable::deleteByFilter([
                'ID' => $ids
            ]);
        }

        return '\Dv\ErrorHandling\Agents\EmailChecker::run();';
    }

    public static function add($interval = 20)
    {
        $date = (new DateTime());

        \CAgent::AddAgent(
            "\\Dv\\ErrorHandling\\Agents\\EmailChecker::run();",
            CommonEnum::MODULE_ID,
            "N",
            $interval * 60,
            "",
            "Y",
            $date->add("+".$interval." minutes")->toString()
        );
    }

    public static function remove()
    {
        \CAgent::RemoveAgent(
            "\Dv\ErrorHandling\Agents\EmailChecker::run();",
            CommonEnum::MODULE_ID
        );
    }

}