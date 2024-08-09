<?php defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED === true ?: die();

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Bitrix\Main\UI\Extension;
use Bitrix\Main\UserTable;
use Dv\ErrorHandling\Agents\EmailChecker;
use Dv\ErrorHandling\Agents\FileChecker;
use Dv\ErrorHandling\Enums\CommonEnum;
use Dv\ErrorHandling\Exceptions\FilePermissionException;
use Dv\ErrorHandling\Helpers\FileHelper;
use Dv\ErrorHandling\Tabs;
use Dv\ErrorHandling\Validator;

Loc::loadMessages(__FILE__);

global $APPLICATION, $USER;

$module_id = basename(dirname(__FILE__));
Loader::includeModule($module_id);

$tabs = Tabs::getTabs();

$options = Tabs::getOptions();


if (check_bitrix_sessid() && strlen($_POST["save"]) > 0) {

    Validator::validate($_POST);

    $arErrors = Validator::getErrors();


    if (empty($arErrors)) {


        foreach ($options as $option) {
            foreach ($option as $subOption) {
                $name = $subOption[0];

                if (is_array($_POST[$name])) {
                    $_REQUEST[$name] = implode(",", array_filter($_POST[$name]));
                    $_POST[$name] = implode(",", array_filter($_POST[$name]));
                }
            }


            __AdmSettingsSaveOptions($module_id, $option);
        }


    }
}

if (!empty($arErrors)) {
    ShowError(implode(' ', $arErrors));
}

$tabControl = new CAdminTabControl("tabControl", $tabs);
$tabControl->Begin();
?>

<form method="POST"
      action="<?php echo $APPLICATION->GetCurPage() ?>?mid=<?= htmlspecialcharsbx($mid) ?>&lang=<?= LANGUAGE_ID ?>"
      name="test22">

    <? foreach ($options as $tabOption) {
        $tabControl->BeginNextTab();
        foreach ($tabOption as $option) {


            $type = $option[3];
            if (is_array($_POST[$option[0]])) {
                $_POST[$option[0]] = implode(',', $_POST[$option[0]]);
            }
            $val = $_POST[$option[0]] ?: COption::GetOptionString($module_id, $option[0], $option[2]);

            ?>
            <tr>
                <td width="40%" <? if ($type[0] == "textarea" || $type[0] == "text-list" || $type[0] == "user_id_multi") echo 'class="adm-detail-valign-top"' ?>>
                    <label for="<? echo htmlspecialcharsbx($option[0]) ?>"><? echo $option[1] ?></label>
                <td width="60%">
                    <?
                    if ($type[0] == "checkbox"):
                        ?><input type="checkbox" name="<? echo htmlspecialcharsbx($option[0]) ?>"
                                 id="<? echo htmlspecialcharsbx($option[0]) ?>"
                                 value="Y"<? if ($val == "Y") echo " checked"; ?>><?
                    elseif ($type[0] == "text"):
                        ?><input type="text" size="<? echo $type[1] ?>" maxlength="255"
                                 value="<? echo htmlspecialcharsbx($val) ?>"
                                 name="<? echo htmlspecialcharsbx($option[0]) ?>"><?
                    elseif ($type[0] == "user_id_multi"):
                        $aVal = isset($val) && is_array($val) ? $val : [$val];
                        $aVal = array_filter($aVal);


                        $aValCount = count($aVal);
                        for ($j = 0; $j < $aValCount; $j++) {
                            $userId = $aVal[$j];
                            $res = "[<a href='/bitrix/admin/user_edit.php?ID=" . $userId . "&lang=" . LANG . "'>" . $userId . "</a>] (" . htmlspecialcharsbx($usersData[$userId]['LOGIN']) . ") " . htmlspecialcharsbx($usersData[$userId]['NAME']) . " " . htmlspecialcharsbx($usersData[$userId]['LAST_NAME']);
                            echo FindUserID(htmlspecialcharsbx($option[0] . '[' . $j . ']'), $userId, $res, "test22", "3", "", " ... ", "", "");
                            ?>
                            <br><?
                        }
                        $jCount = $aValCount + 1;

                        for ($j = $jCount; $j < $jCount + $type[1]; $j++) {
                            $userId = $aVal[$j];
                            echo FindUserID(htmlspecialcharsbx($option[0] . '[' . $j . ']'), $userId, null, "test22", "3", "", " ... ", "", "");
                            ?>
                            <br><?
                        }
                        ?>
                    <?
                    elseif ($type[0] == "number"):
                        ?><input type="text" size="<? echo $type[1] ?>" maxlength="255"
                                 value="<? echo htmlspecialcharsbx($val) ?>"
                                 name="<? echo htmlspecialcharsbx($option[0]) ?>"
                                 onkeypress="return isNumberKey(event)"><?
                    elseif ($type[0] == "textarea"):
                        ?><textarea rows="<? echo $type[1] ?>" cols="<? echo $type[2] ?>"
                                    name="<? echo htmlspecialcharsbx($option[0]) ?>"><? echo htmlspecialcharsbx($val) ?></textarea><?
                    elseif ($type[0] == "text-list" || $type[0] == "srlz-list"):
                        if ($type[0] == "srlz-list") {
                            $aVal = !empty($val) ? unserialize($val, ['allowed_classes' => false]) : '';
                        } else {
                            $aVal = explode(",", $val);
                        }
                        $aVal = is_array($aVal) ? $aVal : [];

                        sort($aVal);
                        $aValCount = count($aVal);
                        for ($j = 0; $j < $aValCount; $j++):
                            ?><input type="text" size="<? echo $type[2] ?>"
                                     value="<? echo htmlspecialcharsbx($aVal[$j]) ?>"
                                     name="<? echo htmlspecialcharsbx($option[0]) . "[]" ?>"><br><?
                        endfor;
                        for ($j = 0; $j < $type[1]; $j++):
                            ?><input type="text" size="<? echo $type[2] ?>" value=""
                                     name="<? echo htmlspecialcharsbx($option[0]) . "[]" ?>"><br><?
                        endfor;
                    elseif ($type[0] == "selectbox"):
                        $arr = $type[1];
                        $arr_keys = array_keys($arr);
                        ?><select name="<? echo htmlspecialcharsbx($option[0]) ?>"><?
                        $arr_keys_count = count($arr_keys);
                        for ($j = 0; $j < $arr_keys_count; $j++):
                            ?>
                            <option value="<? echo $arr_keys[$j] ?>"<? if ($val == $arr_keys[$j]) echo "selected" ?>><? echo htmlspecialcharsbx($arr[$arr_keys[$j]]) ?></option><?
                        endfor;
                        ?></select><?
                    elseif ($type[0] == "multiselectbox"):
                        $arr = $type[1];
                        if (!is_array($arr))
                            $arr = [];
                        $arr_val = explode(",", $val);
                        ?><select
                        size="5" <? if (isset($arControllerOption[$option[0]])) echo ' disabled title="' . GetMessage("MAIN_ADMIN_SET_CONTROLLER_ALT") . '"'; ?>
                        multiple name="<?= htmlspecialcharsbx($option[0]) ?>[]"><?
                        foreach ($arr as $key => $v):
                            ?>
                            <option value="<? echo $key ?>"<? if (in_array($key, $arr_val)) echo " selected" ?>><? echo htmlspecialcharsbx($v) ?></option><?
                        endforeach;
                        ?></select><?
                    endif;

                    ?></td>
            </tr>
            <?
        }
    } ?>


    <?php $tabControl->Buttons(["btnApply" => false, "btnCancel" => false, "btnSaveAndAdd" => false]); ?>
    <?= bitrix_sessid_post(); ?>
    <?php $tabControl->End(); ?>


    <script>
        function isNumberKey(evt) {
            let charCode = (evt.which) ? evt.which : evt.keyCode;
            return !(charCode > 31 && (charCode < 48 || charCode > 57));
        }

    </script>
