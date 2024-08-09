<?php
use Bitrix\Main\Localization\Loc;
use Dv\ErrorHandling\Enums\CommonEnum;

if (!check_bitrix_sessid()) {
    return;
}

?>
<form action="<?=$APPLICATION->GetCurPage()?>">
    <?=bitrix_sessid_post()?>
    <input type="hidden" name="lang" value="<?=LANGUAGE_ID?>">
    <input type="hidden" name="step" value="2">
    <input type="hidden" name="id" value="<?=CommonEnum::MODULE_ID?>">
    <input type="hidden" name="uninstall" value="Y">
    <?CAdminMessage::showMessage(
        Loc::getMessage("DV_ERRORHANDLING_SAVE_DATA")
        )?>
    <input type="submit" name="delete" value="<?=Loc::getMessage("DV_ERRORHANDLING_DELETE")?>">
    <input type="submit" name="savedata" value="<?=Loc::getMessage("DV_ERRORHANDLING_SAVE")?>">
</form>
