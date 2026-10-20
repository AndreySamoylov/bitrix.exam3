<?php

namespace Academy\InvestmentProject\Integration\UI;

use Bitrix\Main\Localization\Loc;

class FieldNameProvider
{
    public function __construct()
    {
        Loc::loadLanguageFile(__FILE__);
    }

    public function getProjectFieldName(string $code): string
    {
        return Loc::getMessage("INVESTMENT_PROJECT_{$code}_FIELD_LABEL") ?? $code;
    }

    public function getHistoryFieldName(string $code): string
    {
        return Loc::getMessage("INVESTMENT_PROJECT_HISTORY_{$code}_FIELD_LABEL") ?? $code;
    }
}