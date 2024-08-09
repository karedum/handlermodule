<?php

namespace Dv\ErrorHandling\Loggers;


use Bitrix\Main\Application;
use Bitrix\Main\Diag\ExceptionHandlerLog;
use Bitrix\Main\Diag\Logger;
use Dv\ErrorHandling\Client\ElasticSearchClient;
use Dv\ErrorHandling\Exceptions\FilePermissionException;
use Dv\ErrorHandling\Handlers\HandlerFactory;
use Dv\ErrorHandling\Helpers\FileHelper;
use Dv\ErrorHandling\Settings;
use Psr\Log\LogLevel;

class ElasticCurlLogger extends Logger
{
    public $client;
    public $type;
    public $index;

    public function __construct($client, $index, $type = '_doc')
    {
        $this->client = $client;
        $this->type = $type;
        $this->index = $index;
    }


    /**
     * Запись лога
     * @param string $level
     * @param string $message
     */
    protected function logMessage(string $level, string $message)
    {
        $this->client->index([
            'index' => $this->index,
            'message' => $message,
            'type' => $this->type,
        ]);
    }


}
