<?php

namespace Academy\InvestmentProject\Integration\UI\Filter;

use Bitrix\Main\UI\Filter\FieldAdapter;

final class ProjectDataProvider extends DataProvider
{
    public function __construct(private readonly ProjectSettings $settings)
    {
    }

    public function getSettings(): ProjectSettings
    {
        return $this->settings;
    }

    public function prepareFieldData($fieldID): ?array
    {
        return match ($fieldID) {
            'RESPONSIBLE_ID', 'UPDATED_BY', 'CREATED_BY' => [
                'params' => [
                    'dialogOptions' => [
                        'entities' => [
                            [
                                'id' => 'user'
                            ]
                        ],
                        'multiple' => true
                    ],
                    'multiple' => true
                ]
            ],
            default => null
        };
    }

    public function prepareFields(): array
    {
        return [
            'ID' => $this->createField(
                'ID',
                [
                    'name' => $this->settings->getFieldName('ID'),
                    'type' => FieldAdapter::NUMBER,
                    'default' => false,
                ]
            ),
            'TITLE' => $this->createField(
                'TITLE',
                [
                    'name' => $this->settings->getFieldName('TITLE'),
                    'type' => FieldAdapter::STRING,
                    'default' => true,

                ]
            ),
            'CREATED_AT' => $this->createField(
                'CREATED_AT',
                [
                    'name' => $this->settings->getFieldName('CREATED_AT'),
                    'type' => FieldAdapter::DATE,
                    'default' => false,
                ]
            ),
            'CREATED_BY' => $this->createField(
                'CREATED_BY',
                [
                    'name' => $this->settings->getFieldName('CREATED_BY'),
                    'type' => FieldAdapter::ENTITY_SELECTOR,
                    'default' => false,
                    'partial' => true,
                ]
            ),
            'UPDATED_AT' => $this->createField(
                'UPDATED_AT',
                [
                    'name' => $this->settings->getFieldName('UPDATED_AT'),
                    'type' => FieldAdapter::DATE,
                    'default' => false,
                ]
            ),
            'UPDATED_BY' => $this->createField(
                'UPDATED_BY',
                [
                    'name' => $this->settings->getFieldName('UPDATED_BY'),
                    'type' => FieldAdapter::ENTITY_SELECTOR,
                    'default' => false,
                    'partial' => true,
                ]
            ),
            'ESTIMATED_COMPLETION_DATE' => $this->createField(
                'ESTIMATED_COMPLETION_DATE',
                [
                    'name' => $this->settings->getFieldName('ESTIMATED_COMPLETION_DATE'),
                    'type' => FieldAdapter::DATE,
                    'default' => true,
                    'partial' => true,
                ]
            ),
            'COMPLETION_DATE' => $this->createField(
                'COMPLETION_DATE',
                [
                    'name' => $this->settings->getFieldName('COMPLETION_DATE'),
                    'type' => FieldAdapter::DATE,
                    'default' => true,
                ]
            ),
            'DESCRIPTION' => $this->createField(
                'DESCRIPTION',
                [
                    'name' => $this->settings->getFieldName('DESCRIPTION'),
                    'type' => FieldAdapter::TEXTAREA,
                    'default' => true,
                ]
            ),
            'RESPONSIBLE_ID' => $this->createField(
                'RESPONSIBLE_ID',
                [
                    'name' => $this->settings->getFieldName('RESPONSIBLE_ID'),
                    'type' => FieldAdapter::ENTITY_SELECTOR,
                    'default' => true,
                    'partial' => true
                ]
            ),
            'COMMENT' => $this->createField(
                'COMMENT',
                [
                    'name' => $this->settings->getFieldName('COMMENT'),
                    'type' => FieldAdapter::TEXTAREA,
                    'default' => true,
                ]
            ),
            'INCOME' => $this->createField(
                'INCOME',
                [
                    'name' => $this->settings->getFieldName('INCOME'),
                    'type' => FieldAdapter::STRING,
                    'default' => true,
                ]
            ),
        ];
    }
}