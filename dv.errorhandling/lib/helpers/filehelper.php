<?php

namespace Dv\ErrorHandling\Helpers;


use Bitrix\Main\Application;
use Dv\ErrorHandling\Exceptions\FilePermissionException;

class FileHelper
{

    /**
     * Удаление файлов старше n дней
     * @param $days
     * @param $path
     */
    public static function removeFilesForDays($days, $path, $extension)
    {

    }

    /**
     * Удаление пустых директорий
     * @param $path
     * @return bool
     */
    public static function removeEmptyFolders($path)
    {

    }


    /**
     * Задает права на папку на уровне продукта и apache.
     * @param $path
     * @throws FilePermissionException
     */
    public static function setPermission($path)
    {
        global $APPLICATION;
        $documentRoot = Application::getDocumentRoot();
        $absolutePath = $documentRoot . $path;
        self::checkFolder($absolutePath);
        $APPLICATION->SetFileAccessPermission($path, ["*" => "D"]);
        file_put_contents($absolutePath . DIRECTORY_SEPARATOR . '.htaccess', 'deny from all');
    }

    /**
     * Проверка директории на существование, создание если не существует, проверка чтения.
     * @param $absolutePath
     * @throws FilePermissionException
     */
    public static function checkFolder($absolutePath)
    {
        if (!is_dir($absolutePath)) {
            if (!mkdir($absolutePath, 0755, true)) {
                throw new FilePermissionException('Ошибка создания папки!');
            }
        } else {
            if (!is_writable($absolutePath)) {
                throw new FilePermissionException('Нет прав на запись в папку!');
            }
        }
    }

    /**
     * Подсчет размера папки в МБ
     * @param $path
     * @return int
     */
    public static function calcDirSize($path)
    {

    }

    /**
     * удаление файлов логов
     * @param $path
     * @param $extension
     * @param $exclude
     */
    public static function deleteOldFiles($path, $extension, $exclude)
    {

    }

    /**
     * Поиск всех файлов лога по паттерну
     * @param $path
     * @param $extension
     * @param $exclude
     * @return array
     */
    public static function findAllFiles($path, $extension, $exclude = null)
    {

    }

}