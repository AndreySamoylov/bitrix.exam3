<?php

namespace Academy\InvestmentProject\Integration\UI\Filter;

use Academy\InvestmentProject\Integration\UI\FieldNameProvider;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Filter\Settings;

final class HistorySettings extends Settings
{
    private readonly FieldNameProvider $fieldNameProvider;

    /**
     * @throws ArgumentException
     */
    public function __construct(string $id, FieldNameProvider $fieldNameProvider)
    {
        parent::__construct(['ID' => $id]);

        $this->fieldNameProvider = $fieldNameProvider;
    }

    public function getFieldName(string $code): string
    {
        return $this->fieldNameProvider->getHistoryFieldName($code);
    }
}