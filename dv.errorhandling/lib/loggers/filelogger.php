<?php

namespace Dv\ErrorHandling\Loggers;


use Bitrix\Main\Application;
use Bitrix\Main\Diag\ExceptionHandlerLog;
use Bitrix\Main\Diag\Logger;
use Dv\ErrorHandling\Exceptions\FilePermissionException;
use Dv\ErrorHandling\Handlers\HandlerFactory;
use Dv\ErrorHandling\Helpers\FileHelper;
use Dv\ErrorHandling\Settings;
use Psr\Log\LogLevel;

class FileLogger extends Logger
{

    private $folderPath;
    private $extension;
    private $totalSizeFiles;

    public function __construct($folderPath, $totalSizeFiles, $extension = 'log')
    {
        $this->folderPath = $folderPath;
        $this->extension = $extension;
        $this->totalSizeFiles = $totalSizeFiles;
    }

    /**
     * Возврат подготовленного файла
     * @return string
     */
    public function prepareFileName()
    {
        return date('Y_m_d') . '.' . $this->extension;
    }

    /**
     * Возврат названия директории (по годам и месяцам)
     * @return string
     */
    public function prepareFolderPath()
    {
        $documentRoot = Application::getDocumentRoot();
        return $documentRoot . DIRECTORY_SEPARATOR . $this->folderPath . DIRECTORY_SEPARATOR . date('Y') . DIRECTORY_SEPARATOR . date('m');
    }

    /**
     * Запись лога
     * @param string $level
     * @param string $message
     */
    protected function logMessage(string $level, string $message)
    {

    }


}
