<?php

use Bitrix\Main\Localization\Loc;

if (!check_bitrix_sessid()) {
    return;
}
if ($errorException = $APPLICATION->getException()) {
    CAdminMessage::showMessage(
        Loc::getMessage("DV_ERRORHANDLING_ERROR_INSTALL").': '.$errorException->GetString()
    );
} else {
    CAdminMessage::showNote(
        Loc::getMessage("DV_ERRORHANDLING_SUCCESS_INSTALL")
    );
}
?>
<form action="<?= $APPLICATION->getCurPage(); ?>">
    <input type="hidden" name="lang" value="<?= LANGUAGE_ID; ?>" />
    <input type="submit" value="<?= Loc::getMessage("DV_ERRORHANDLING_BACK_TO_MODULES")?>">
</form>