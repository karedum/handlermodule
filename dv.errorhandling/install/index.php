<?php B_PROLOG_INCLUDED === true || die();

IncludeModuleLangFile(__FILE__);

use Bitrix\Main\Config\Option;
use Bitrix\Main\EventManager;
use Bitrix\Main\HttpApplication;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Type\DateTime;
use Dv\ErrorHandling\Model\ExceptionsTable;
use Dv\OpenMicrophone\Menu\LeftMenu;
use Dv\OpenMicrophone\Migration\Messages;

Loc::loadMessages(__FILE__);

class dv_errorhandling extends CModule
{

    const MODULE_ID = 'dv.errorhandling';
    var $MODULE_ID = 'dv.errorhandling';
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $PARTNER_NAME;
    var $PARTNER_URI;

    private EventManager $eventManager;

    /**
     * Массив событий для регистрации
     * @var array|string[][]
     */
    private static array $events = [

    ];

    public function __construct()
    {
        $arModuleVersion = [];
        include(__DIR__ . "/version.php");
        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME = Loc::getMessage("DV.ERRORHANDLING.MODULE_NAME");
        $this->MODULE_DESCRIPTION = Loc::getMessage("DV.ERRORHANDLING.MODULE_DESC");

        $this->PARTNER_NAME = Loc::getMessage("DV.ERRORHANDLING.PARTNER_NAME");
        $this->PARTNER_URI = Loc::getMessage("DV.ERRORHANDLING.PARTNER_URI");

        $this->eventManager = EventManager::getInstance();
    }

    /**
     * @return void
     */
    public function doInstall(): void
    {
        global $APPLICATION;
        try {
            ModuleManager::registerModule($this->MODULE_ID);
            Loader::includeModule($this->MODULE_ID);
            $this->checkDependence();
            $this->registerEvents();
            $this->installFiles();
            $this->InstallDB();

        } catch (Throwable $e) {
            $APPLICATION->ThrowException($e->getMessage());
            $this->unInstall();
        }
        $APPLICATION->IncludeAdminFile($this->MODULE_NAME, __DIR__ . '/step.php');
    }

    /**
     * Проверка зависимостей перед установкой модуля
     * @return void
     * @throws \Bitrix\Main\LoaderException
     */
    public function checkDependence(): void
    {
        if (!CheckVersion(ModuleManager::getVersion("main"), "21.0.100")) {
            throw new Exception(Loc::getMessage('DV.ERRORHANDLING.VERSION_ERROR'));
        }
    }

    /**
     * Регистрация событий
     * @return void
     */
    public function registerEvents(): void
    {
        foreach (self::$events as $event) {
            $this->eventManager->registerEventHandler(...$event);
        }
    }

    /**
     * Удаление событий
     * @return void
     */
    public function unRegisterEvents(): void
    {
        foreach (self::$events as $event) {
            $this->eventManager->unRegisterEventHandler(...$event);
        }
    }

    /**
     * Деинсталляция модуля
     * @return void
     */
    public function doUninstall(): void
    {
        global $APPLICATION, $step;
        Loader::includeModule($this->MODULE_ID);
        try {
            $step = intval($step);
            if ($step < 2) {
                $APPLICATION->IncludeAdminFile($this->MODULE_NAME, __DIR__ . '/unstep1.php');
            } elseif ($step == 2) {
                $requestData = HttpApplication::getInstance()->getContext()->getRequest()->getValues();
                if ($requestData['delete']) {
                    Option::delete($this->MODULE_ID);
                    $this->UnInstallDB();
                }
                $this->unInstall();
            }
        } catch (Throwable $e) {
            $APPLICATION->ThrowException($e->getMessage());
        }
        $APPLICATION->IncludeAdminFile($this->MODULE_NAME, __DIR__ . '/unstep2.php');
    }

    /**
     * Деинсталляция модуля без удаления данных
     * @return void
     */
    public function unInstall(): void
    {
        $this->unInstallFiles();
        $this->unRegisterEvents();
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }


    /**
     * Копирование файлов компонентов
     * @return void
     */
    public function installFiles(): void
    {

    }

    /**
     * Удаление файлов компонентов
     * @return void
     */
    public function unInstallFiles(): void
    {

    }

    /**
     * @return bool
     */
    public function InstallDB()
    {
        ExceptionsTable::createTable();
        $this->installEmailEvents();
        return true;
    }

    /**
     * @return bool
     */
    public function UnInstallDB()
    {
        ExceptionsTable::deleteTable();
        $this->deleteEmailEvents();
        return true;
    }

    public function installEmailEvents()
    {
        $et = new CEventType;
        $et->Add([
            'LID' => 'ru',
            'EVENT_NAME' => 'ERRORHANDLING_MESSAGE',
            'EVENT_TYPE' => 'email',
            'NAME' => 'Ошибки',
            'DESCRIPTION' => '',
            'SORT' => '150',
        ]);
        $et->Add([
            'LID' => 'en',
            'EVENT_NAME' => 'ERRORHANDLING_MESSAGE',
            'EVENT_TYPE' => 'email',
            'NAME' => 'Ошибки',
            'DESCRIPTION' => '',
            'SORT' => '150',
        ]);
        $em = new CEventMEssage;
        $arFields = [
            'EVENT_NAME' => 'ERRORHANDLING_MESSAGE',
            'LID' =>
                [
                    0 => 's1',
                ],
            'ACTIVE' => 'Y',
            'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
            'EMAIL_TO' => '#EMAIL_TO#',
            'SUBJECT' => 'Новые Exceptions',
            'MESSAGE' => 'Новых Exceptions - #COUNT#: #MESSAGE#',
            'BODY_TYPE' => 'text',
            'BCC' => '',
            'REPLY_TO' => '',
            'CC' => '',
            'IN_REPLY_TO' => '',
            'PRIORITY' => '',
            'FIELD1_NAME' => '',
            'FIELD1_VALUE' => '',
            'FIELD2_NAME' => '',
            'FIELD2_VALUE' => '',
            'SITE_TEMPLATE_ID' => '',
            'ADDITIONAL_FIELD' => [],
            'LANGUAGE_ID' => '',
            'EVENT_TYPE' => '[ ERRORHANDLING_MESSAGE ] Ошибки',
        ];
        $result = $em->Add($arFields);
    }

    public function deleteEmailEvents()
    {
        CEventType::Delete('ERRORHANDLING_MESSAGE');
        $arFilter = [
            "EVENT_NAME" => "ERRORHANDLING_MESSAGE",
        ];
        $rsMess = CEventMessage::GetList("site_id", "desc", $arFilter)->Fetch();
        if ($rsMess['ID']) {
            CEventMEssage::Delete($rsMess['ID']);
        }
    }
}