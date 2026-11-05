<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Bizproc\FieldType;

$arActivityDescription = [
	"NAME" => Loc::getMessage("EXAM31_TICKET_TRAIN_ACTIVITY_DESCR_NAME"),
	"DESCRIPTION" => Loc::getMessage("EXAM31_TICKET_TRAIN_ACTIVITY_DESCR_DESCR"),
	"TYPE" => "activity",
	"CLASS" => "ExamTicketTrainActivity",
	"JSCLASS" => "BizProcActivity",
	"CATEGORY" => [
		"ID" => "other",
	],
	"RETURN" => [
		"ID" => [
			"NAME" => Loc::getMessage("EXAM31_TICKET_TRAIN_ACTIVITY_RESULT_ID"),
			"TYPE" => FieldType::INT,
		],
		"ACTIVE" => [
			"NAME" => Loc::getMessage("EXAM31_TICKET_TRAIN_ACTIVITY_RESULT_ACTIVE"),
			"TYPE" => FieldType::BOOL,
		],
        "DATE_MODIFY" => [
            "NAME" => Loc::getMessage("EXAM31_TICKET_TRAIN_ACTIVITY_RESULT_DATE_MODIFY"),
            "TYPE" => FieldType::DATETIME,
        ],
        "TITLE" => [
            "NAME" => Loc::getMessage("EXAM31_TICKET_TRAIN_ACTIVITY_RESULT_TITLE"),
            "TYPE" => FieldType::STRING,
        ],
        "TEXT" => [
            "NAME" => Loc::getMessage("EXAM31_TICKET_TRAIN_ACTIVITY_RESULT_TEXT"),
            "TYPE" => FieldType::TEXT,
        ],
    ],
];

