<?php
defined('B_PROLOG_INCLUDED') || die;

use Exam31\Ticket\ExamFieldType;
use Bitrix\Main\Component\BaseUfComponent;
use Bitrix\Main\Loader;
use Bitrix\Main\Text\HtmlFilter;

class SomeElementFieldComponent extends BaseUfComponent
{

	public function __construct($component = null)
	{
		Loader::requireModule('exam31.ticket');
		parent::__construct($component);
	}

	protected static function getUserTypeId(): string
	{
		return ExamFieldType::USER_TYPE_ID;
	}

	public function prepareValue($value)
	{
		$preparedValue = [
			'VALUE' => (int) $value,
		];

		$formatValueTemplate = HtmlFilter::encode((string) $this->arResult['userField']['SETTINGS']['FORMAT'] ?? '#ID#');
        $formatInfoDetailPage = HtmlFilter::encode((string) $this->arResult['userField']['SETTINGS']['INFO_DETAIL_PAGE']);

		$preparedValue['FORMATTED_VALUE'] = str_replace(
			'#ID#',
			$preparedValue['VALUE'],
			$formatValueTemplate
		);

        $formatInfoDetailPage = str_replace(
            '#ID#',
            $preparedValue['VALUE'],
            $formatInfoDetailPage
        );

        $preparedValue['FORMATTED_VALUE'] = sprintf(
            '<a href="%s" target="_blank">%s</a>',
            $formatInfoDetailPage,
            $preparedValue['FORMATTED_VALUE'],
        );

		return $preparedValue;

	}
}