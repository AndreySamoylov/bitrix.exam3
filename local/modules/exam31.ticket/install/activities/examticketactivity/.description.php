<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

use \Bitrix\Main\Localization\Loc;
use Bitrix\Bizproc\FieldType;

$arActivityDescription = [
	"NAME" => Loc::getMessage("EXAM31_TICKET_ACTIVITY_DESCR_NAME"),
	"DESCRIPTION" => Loc::getMessage("EXAM31_TICKET_ACTIVITY_DESCR_DESCR"),
	"TYPE" => "activity",
	"CLASS" => "ExamTicketActivity",
	"JSCLASS" => "BizProcActivity",
	"CATEGORY" => [
		"ID" => "other",
	],
	"RETURN" => [
		"ID" => [
			"NAME" => Loc::getMessage("EXAM31_TICKET_ACTIVITY_RESULT_ID"),
			"TYPE" => FieldType::INT,
		],
        'DATE_MODIFY' => [
            "NAME" => Loc::getMessage("EXAM31_TICKET_ACTIVITY_RESULT_DATE_MODIFY"),
            'Type' => FieldType::DATETIME
        ],
        'ACTIVE' => [
            "NAME" => Loc::getMessage("EXAM31_TICKET_ACTIVITY_RESULT_ACTIVE"),
            'Type' => FieldType::BOOL
        ],
        'TITLE' => [
            "NAME" => Loc::getMessage("EXAM31_TICKET_ACTIVITY_RESULT_TITLE"),
            'Type' => FieldType::STRING
        ],
        'TEXT' => [
            "NAME" => Loc::getMessage("EXAM31_TICKET_ACTIVITY_RESULT_TEXT"),
            'Type' => FieldType::TEXT
        ],
	],
];

?>