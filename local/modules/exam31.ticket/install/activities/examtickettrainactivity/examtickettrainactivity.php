<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;
use Bitrix\Bizproc\Activity\BaseActivity;
use Bitrix\Bizproc\FieldType;
use Bitrix\Main\ErrorCollection;
use Bitrix\Bizproc\Activity\PropertiesDialog;
use Bitrix\Main\Text\HtmlFilter;
use Exam31\Ticket\Ex3120SomeElementTable;

class CBPExamTicketTrainActivity extends BaseActivity
{
	public function __construct($name)
    {
        parent::__construct($name);

        $this->arProperties = [
            'ID' => 0,
            'ACTIVE' => null,
            'DATE_MODIFY' => null,
            'TITLE' => null,
            'TEXT' => null,
        ];

        $this->SetPropertiesTypes([
            'ID' => ['Type' => FieldType::STRING],
            'ACTIVE' => ['Type' => FieldType::BOOL],
            'DATE_MODIFY' => ['Type' => FieldType::DATETIME],
            'TITLE' => ['Type' => FieldType::STRING],
            'TEXT' => ['Type' => FieldType::TEXT],
        ]);
    }

	protected static function getFileName(): string
	{
		return __FILE__;
	}

	protected function internalExecute(): ErrorCollection
	{
		$errors = parent::internalExecute();

		$elementId = (int) $this->preparedProperties["ID"];
        $element = Ex3120SomeElementTable::query()
            ->addSelect('*')
            ->where('ID', '=', $elementId)
            ->fetch();



		if(!empty($element))
		{
			$this->preparedProperties['ID'] = HtmlFilter::encode($element['ID']);
			$this->preparedProperties['ACTIVE'] = HtmlFilter::encode($element['ACTIVE']);
			$this->preparedProperties['DATE_MODIFY'] = HtmlFilter::encode($element['DATE_MODIFY']);
			$this->preparedProperties['TITLE'] = HtmlFilter::encode($element['TITLE']);
			$this->preparedProperties['TEXT'] = HtmlFilter::encode($element['TEXT']);
		}
		else
		{
			$this->preparedProperties['ID'] = 0;
			$this->preparedProperties['TITLE'] = 'Данные не найдены';

			$this->log(
				Loc::getMessage(
					'EXAM31_TICKET_TRAIN_ACTIVITY_LOG_TEXT_N',
					[
						'#ID#' => $elementId,
					]
				)
			);
		}

		return $errors;
	}

	public static function getPropertiesDialogMap(?PropertiesDialog $dialog = null): array
	{
		$map = [
			'ID' => [
				'Name' => 'ID',
				'FieldName' => 'ID',
				'Type' => FieldType::INT,
				'Required' => true,
				'Default' => '',
				'Options' => [],
			],
		];
		
		return $map;
	}
}