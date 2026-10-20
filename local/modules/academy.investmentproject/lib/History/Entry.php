<?php

namespace Academy\InvestmentProject\History;

use Bitrix\Main\Type\DateTime;

final class Entry
{
    public function __construct(
        public readonly ?int $id,
        public readonly int $projectId,
        public readonly int $authorId,
        public readonly string $fieldName,
        public readonly ?string $previousValue,
        public readonly ?string $currentValue,
        public readonly DateTime $changedAt
    ) {
    }

    public function withId(int $id): Entry
    {
        return new Entry(
            $id,
            $this->projectId,
            $this->authorId,
            $this->fieldName,
            $this->previousValue,
            $this->currentValue,
            $this->changedAt
        );
    }
}