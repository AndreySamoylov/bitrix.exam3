<?php
namespace Exam31\Ticket;

use Bitrix\Main\UserField\Types\BaseType;
use Bitrix\Main\Localization\Loc;

class ExamTrainFieldType extends BaseType
{
	public const USER_TYPE_ID = 'examtrainfieldtype';
	public const RENDER_COMPONENT = 'exam31.ticket:train.examfieldtype';

	protected static function getDescription(): array
	{
		return [
			'DESCRIPTION' => Loc::getMessage('EXAM31_TICKET_TRAIN_FIELDTYPE_UF_DESCRIPTION'),
			'BASE_TYPE' => \CUserTypeManager::BASE_TYPE_INT,
		];
	}

	public static function getDbColumnType(): string
	{
		return 'int';
	}

	public static function prepareSettings(array $userField): array
	{
		return [
			'FORMAT' => $userField['SETTINGS']['FORMAT'] ?: Loc::getMessage('EXAM31_TICKET_TRAIN_FIELDTYPE_UF_DEFAULT_TEMPLATE_VALUE'),
			'INFO_DETAIL_PAGE' => $userField['SETTINGS']['INFO_DETAIL_PAGE'] ?: Loc::getMessage('EXAM31_TICKET_TRAIN_FIELDTYPE_UF_DEFAULT_DETAIL_PAGE_FORMAT'),
		];
	}
}