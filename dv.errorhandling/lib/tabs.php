<?php

namespace Dv\ErrorHandling;


use Bitrix\Main\Config\Option;
use Dv\ErrorHandling\Enums\CommonEnum;
use Bitrix\Main\Localization\Loc;

class Tabs
{
    public static function getTabs()
    {
        return [
            [
                "DIV" => "general",
                "TAB" => Loc::getMessage("DV_ERRORHANDLING_TAB_GENERAL"),
                "TITLE" => Loc::getMessage("DV_ERRORHANDLING_TAB_GENERAL")
            ],
            [
                "DIV" => "files",
                "TAB" => Loc::getMessage("DV_ERRORHANDLING_TAB_FILES"),
                "TITLE" => Loc::getMessage("DV_ERRORHANDLING_TAB_FILES")
            ],
            [
                "DIV" => "email",
                "TAB" => Loc::getMessage("DV_ERRORHANDLING_TAB_EMAIL"),
                "TITLE" => Loc::getMessage("DV_ERRORHANDLING_TAB_EMAIL")
            ],
            [
                "DIV" => "elasticsearch",
                "TAB" => Loc::getMessage("DV_ERRORHANDLING_TAB_ELASTICHSEARCH"),
                "TITLE" => Loc::getMessage("DV_ERRORHANDLING_TAB_ELASTICHSEARCH")
            ]
        ];
    }

    public static function getOptions()
    {
        return [
            "general" => [
                [
                    "",
                    Loc::getMessage("DV_ERRORHANDLING_TRACE_ARGS"),
                    "",
                    ['selectbox',
                        [
                            0 => 'Не выбрано',
                            'all' => 'Удалять из трассировки ВСЕ агрументы функций',
                            'class' => 'Заменять большие объекты классов в аргументах на пустые объекты'
                        ],
                    ]
                ],
                [
                    "",
                    Loc::getMessage("DV_ERRORHANDLING_TRACE_LENGTH"),
                    "",
                    ["number", 20]
                ],
                [
                    "",
                    Loc::getMessage("DV_ERRORHANDLING_LENGTH_MESSAGES"),
                    "",
                    ["number", 20]
                ],
                [
                    "",
                    Loc::getMessage("DV_ERRORHANDLING_CHANNELS"),
                    "",
                    [
                        '',
                        [
                            'journal' => 'Журнал событий',
                            'elasticsearch' => 'Elasticsearch',
                            'email' => 'Email',
                            'files' => 'Файлы',
                        ],
                    ]
                ],
            ],
            "files" => [
                [
                    "",
                    Loc::getMessage("DV_ERRORHANDLING_DAYS_STORE_FILE"),
                    "0",
                    ["number", 144]
                ],
                [
                    "",
                    Loc::getMessage("DV_ERRORHANDLING_FOLDER_PATH"),
                    "",
                    ["text", 144]
                ],
                [
                    "",
                    Loc::getMessage("DV_ERRORHANDLING_TOTAL_SIZE_FILES"),
                    "",
                    ["number", 144]
                ],
                [
                    "",
                    Loc::getMessage("DV_ERRORHANDLING_FILE_EXTENSIONS"),
                    "log",
                    ["text", 20]
                ],
            ],
            "email" => [
                [
                    "",
                    Loc::getMessage("DV_ERRORHANDLING_EMAIL_TYPE_SEND"),
                    "",
                    ['selectbox',
                        [
                            'hit' => 'На хите',
                            'schedule' => 'По расписанию'],
                    ]
                ],
                [
                    "",
                    Loc::getMessage("DV_ERRORHANDLING_SENDING_INTERVAL"),
                    "",
                    ["text", 144]
                ],
                [
                    "",
                    'Кому отправлять (выбор пользователя)',
                    "",
                    ["user_id_multi", 3]
                ],
                [
                    "",
                    'Кому отправлять (Email)',
                    "",
                    ["text-list", 3]
                ],

            ],
            "elasticsearch" => [
                [
                    "",
                    Loc::getMessage("DV_ERRORHANDLING_ELASTIC_TYPE_CONNECT"),
                    "",
                    ['selectbox',
                        [
                            'curl' => 'CURL',
//                            'lib' => 'Библиотека'
                        ],
                    ]
                ],
                [
                    "",
                    Loc::getMessage("DV_ERRORHANDLING_ELASTIC_HOST"),
                    "",
                    ["text", 144]
                ],
                [
                    "",
                    Loc::getMessage("DV_ERRORHANDLING_ELASTIC_USER"),
                    "",
                    ["text", 144]
                ],
                [
                    "",
                    Loc::getMessage("DV_ERRORHANDLING_ELASTIC_PASSWORD"),
                    "",
                    ["text", 144]
                ],
            ],
        ];
    }


}