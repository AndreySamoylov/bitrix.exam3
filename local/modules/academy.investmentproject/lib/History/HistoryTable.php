<?php

namespace Academy\InvestmentProject\History;

use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\DatetimeField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\TextField;

final class HistoryTable extends DataManager
{
    public static function getTableName(): string
    {
        return 'academy_investment_project_history';
    }

    public static function getMap(): array
    {
        return [
            (new IntegerField('ID'))->configurePrimary()->configureAutocomplete(),
            (new IntegerField('PROJECT_ID'))->configureRequired(),
            (new IntegerField('AUTHOR_ID'))->configureRequired(),
            (new StringField('FIELD_NAME'))->configureRequired(),
            (new TextField('PREVIOUS_VALUE')),
            (new TextField('CURRENT_VALUE')),
            (new DatetimeField('CHANGED_AT'))->configureRequired()
        ];
    }
}