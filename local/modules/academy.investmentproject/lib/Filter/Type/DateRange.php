<?php

namespace Academy\InvestmentProject\Filter\Type;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\ORM\Query\Filter\ConditionTree;
use Bitrix\Main\Type\DateTime;
use LogicException;
use RuntimeException;

/**
 * Вспомогательный класс для фильтрации по временному промежутку.
 */
final class DateRange
{
    public readonly ?DateTime $since;
    public readonly ?DateTime $until;

    public function __construct(?DateTime $since, ?DateTime $until)
    {
        if (isset($since, $until) && $since->getTimestamp() > $until->getTimestamp()) {
            throw new LogicException('Beginning of date range cannot be more than ending of date range.');
        }

        $this->since = $since;
        $this->until = $until;
    }

    public static function createFromArray(array $filter, string $fieldName): DateRange
    {
        $sinceField = '>=' . $fieldName;
        if (!empty($filter[$sinceField])) {
            $since = DateTime::tryParse($filter[$sinceField]);
        }

        $untilField = '<=' . $fieldName;
        if (!empty($filter[$untilField])) {
            $until = DateTime::tryParse($filter[$untilField]);
        }

        return new DateRange($since ?? null, $until ?? null);
    }

    public function applyTo(ConditionTree $criteria, string $fieldName): void
    {
        try {
            if (isset($this->since)) {
                $criteria->where($fieldName, '>=', $this->since);
            }
            if (isset($this->until)) {
                $criteria->where($fieldName, '<=', $this->until);
            }
        } catch (ArgumentException $e) {
            // Never happens.
            throw new RuntimeException($e->getMessage(), previous: $e);
        }
    }
}