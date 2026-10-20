<?php

namespace Academy\InvestmentProject\History;

use Academy\InvestmentProject\Filter\Type\DateRange;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\ORM\Query\Filter\ConditionTree;

final class Filter
{
    public function __construct(
        public readonly ?int $projectId,
        public readonly ?array $fieldNames,
        public readonly ?string $previousValue,
        public readonly ?string $currentValue,
        public readonly ?DateRange $createdAt,
        public readonly ?array $authorIds
    ) {
    }

    public function toCriteria(): ConditionTree
    {
        $criteria = new ConditionTree();

        try {
            if (isset($this->projectId)) {
                $criteria->where('PROJECT_ID', '=', $this->projectId);
            }

            if (!empty($this->fieldNames)) {
                $criteria->whereIn('FIELD_NAME', $this->fieldNames);
            }

            if (isset($this->previousValue)) {
                $previousValue = str_replace('%', '%%', $this->previousValue);
                $criteria->whereLike('PREVIOUS_VALUE', $previousValue);
            }

            if (isset($this->currentValue)) {
                $currentValue = str_replace('%', '%%', $this->currentValue);
                $criteria->whereLike('CURRENT_VALUE', $currentValue);
            }

            if (isset($this->createdAt)) {
                $this->createdAt->applyTo($criteria, 'CREATED_AT');
            }

            if (!empty($this->authorIds)) {
                $criteria->whereIn('AUTHOR_ID', $this->authorIds);
            }
        } catch (ArgumentException) {
            // noop, never happens.
        }

        return $criteria;
    }
}