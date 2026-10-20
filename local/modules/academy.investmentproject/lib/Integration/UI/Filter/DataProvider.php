<?php

namespace Academy\InvestmentProject\Integration\UI\Filter;

use Academy\InvestmentProject\Filter\Type\DateRange;
use Bitrix\Main\Filter\DataProvider as MainDataProvider;
use Bitrix\Main\Filter\Field;
use Bitrix\Main\UI\Filter\FieldAdapter;

abstract class DataProvider extends MainDataProvider
{
    public function prepareFilterValue(array $rawFilterValue): array
    {
        foreach ($this->prepareFields() as $field) {
            $rawFilterValue[$field->getId()] = match ($field->getType()) {
                FieldAdapter::DATE => DateRange::createFromArray($rawFilterValue, $field->getId()),
                default => $rawFilterValue[$field->getId()]
            };
        }

        return $rawFilterValue;
    }

    public function getFieldSortingName(Field $field): ?string
    {
        return $field->getType() !== FieldAdapter::ENTITY_SELECTOR ? $field->getId() : null;
    }
}