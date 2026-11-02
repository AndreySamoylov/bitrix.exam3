<?php
defined('B_PROLOG_INCLUDED') || die;

use Exam31\Ticket\ExamFieldType;
use Exam31\Ticket\AndreyElementTable;
use Bitrix\Main\Localization\Loc;
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

        $element = AndreyElementTable::query()
            ->addSelect('*')
            ->where('ID', '=', $preparedValue['VALUE'])
            ->fetch();

        $link = null;
        if (empty($element)) {
            $title = Loc::getMessage('EXAM31_EXAM_FIELD_TYPE_ELEMENT_NOT_FOUND');
        } else {
            $title = $element['TITLE'];
            $link = str_replace(
                '#ID#',
                $element['ID'],
                $this->arResult['userField']['SETTINGS']['DETAIL_PAGE_FORMAT']
            ) ?? '';
        }

        $formatValueTemplate = HtmlFilter::encode((string) $this->arResult['userField']['SETTINGS']['FORMAT'] ?? '#ID#');

        $preparedValue['FORMATTED_VALUE'] = str_replace(
            ['#ID#', '#TITLE#'],
            [$preparedValue['VALUE'], $title],
            $formatValueTemplate
        );

        if (!empty($element) && !empty($link)) {
            $preparedValue['FORMATTED_VALUE'] = sprintf('<a href="%s">%s</a>', $link, $preparedValue['FORMATTED_VALUE']);
        }

		return $preparedValue;
	}
}