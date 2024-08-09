<?php

namespace Dv\ErrorHandling\Loggers;


use Bitrix\Main\Application;
use Bitrix\Main\Diag\ExceptionHandlerLog;
use Bitrix\Main\Diag\Logger;
use Bitrix\Main\Mail\Event;
use Dv\ErrorHandling\Exceptions\FilePermissionException;
use Dv\ErrorHandling\Handlers\HandlerFactory;
use Dv\ErrorHandling\Helpers\FileHelper;
use Dv\ErrorHandling\Settings;
use Psr\Log\LogLevel;

class EmailLogger extends Logger
{

    private $emails;


    public function __construct($emails)
    {
        $this->emails = array_unique($emails);
    }


    /**
     * Запись лога
     * @param string $level
     * @param string $message
     */
    protected function logMessage(string $level, string $message)
    {
        foreach ($this->emails as $email) {
            Event::sendImmediate([
                "EVENT_NAME" => "ERRORHANDLING_MESSAGE",
                "LID" => "s1",
                "C_FIELDS" => [
                    "EMAIL_TO" => $email,
                    "MESSAGE" => $message,
                    'COUNT' => $this->context['COUNT']
                ]
            ]);
        }

    }


}
