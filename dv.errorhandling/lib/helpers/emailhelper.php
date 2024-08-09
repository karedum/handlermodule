<?php

namespace Dv\ErrorHandling\Helpers;


use Bitrix\Main\Application;
use Bitrix\Main\UserTable;
use Dv\ErrorHandling\Exceptions\FilePermissionException;
use Dv\ErrorHandling\Settings;

class EmailHelper
{


    public static function getEmails()
    {
        $sendUsersId = explode(',', Settings::get('send_users_id') ?? '');
        $sendEmails = explode(',', Settings::get('send_emails') ?? '');
        if (!empty($sendUsersId)) {
            $usersRes = UserTable::getList([
                'filter' => [
                    'ID' => $sendUsersId
                ],
                'select' => [
                    'EMAIL',
                ]
            ]);
            while ($user = $usersRes->fetch()) {
                $sendEmails[] = $user['EMAIL'];
            }
        }
        return array_unique($sendEmails);
    }

}