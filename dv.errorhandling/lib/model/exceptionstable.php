<?php

namespace Dv\ErrorHandling\Model;

use Bitrix\Main\Entity;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Data\Internal\DeleteByFilterTrait;
use Bitrix\Main\Type\DateTime;

class ExceptionsTable extends DataManager
{
    use DeleteByFilterTrait;

    public static function getTableName()
    {
        return 'b_errorhandling_exceptions';
    }

    public static function getMap()
    {
        return array(
            new Entity\IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true
            ]),
            (new Entity\StringField('EXCEPTION_NAME'))
                ->configureRequired(),
            new Entity\StringField('MESSAGE'),
            new Entity\StringField('LOG_LEVEL'),
            new Entity\DatetimeField('DATE_CREATE', [
                'default_value' => function () {
                    return new DateTime();
                },
            ]),

        );
    }

    public static function createTable()
    {
        try {
            self::getEntity()->createDbTable();
        } catch (\Bitrix\Main\DB\SqlQueryException $e) {
            return false;
        }
        return true;
    }

    public static function deleteTable()
    {
        $sql = "DROP TABLE IF EXISTS ".self::getTableName();
        self::getEntity()->getConnection()->query($sql);
    }
}