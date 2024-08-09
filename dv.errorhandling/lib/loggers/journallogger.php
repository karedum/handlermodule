<?php

namespace Dv\ErrorHandling\Loggers;


use Bitrix\Main\Diag\Logger;

class JournalLogger extends Logger
{

    private $auditTypeId;
    private $itemId;
    private $moduleId;

    public function __construct($moduleId, $itemId, $auditTypeId)
    {
        $this->moduleId = $moduleId;
        $this->itemId = $itemId;
        $this->auditTypeId = $auditTypeId;
    }

    protected function logMessage(string $level, string $message)
    {
        \CEventLog::Add([
            "SEVERITY" => $level,
            "AUDIT_TYPE_ID" => $this->auditTypeId,
            "MODULE_ID" => $this->moduleId,
            "ITEM_ID" => $this->itemId,
            "DESCRIPTION" => $message,
        ]);
    }

}
