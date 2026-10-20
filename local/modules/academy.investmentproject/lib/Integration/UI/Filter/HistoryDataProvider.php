<?php

namespace Academy\InvestmentProject\Integration\UI\Filter;

use Bitrix\Main\UI\Filter\FieldAdapter;

final class HistoryDataProvider extends DataProvider
{
    public function __construct(
        private readonly ProjectDataProvider $projectDataProvider,
        private readonly HistorySettings $settings
    ) {
    }

    public function getSettings(): HistorySettings
    {
        return $this->settings;
    }

    public function prepareFieldData($fieldID): ?array
    {
        if ($fieldID === 'FIELD_NAME') {
            $projectFields = [];
            foreach ($this->projectDataProvider->prepareFields() as $field) {
                $projectFields[$field->getId()] = $field->getName();
            }
            return [
                'items' => $projectFields,
                'params' => [
                    'multiple' => true
                ]
            ];
        }
        if ($fieldID === 'AUTHOR_ID') {
            return [
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
            ];
        }
        return null;
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
            'PROJECT_ID' => $this->createField(
                'PROJECT_ID',
                [
                    'name' => $this->settings->getFieldName('PROJECT_ID'),
                    'type' => FieldAdapter::NUMBER,
                    'default' => false,
                ]
            ),
            'AUTHOR_ID' => $this->createField(
                'AUTHOR_ID',
                [
                    'name' => $this->settings->getFieldName('AUTHOR_ID'),
                    'type' => FieldAdapter::ENTITY_SELECTOR,
                    'default' => true,
                    'partial' => true
                ]
            ),
            'FIELD_NAME' => $this->createField(
                'FIELD_NAME',
                [
                    'name' => $this->settings->getFieldName('FIELD_NAME'),
                    'type' => FieldAdapter::LIST,
                    'default' => true,
                    'partial' => true
                ]
            ),
            'PREVIOUS_VALUE' => $this->createField(
                'PREVIOUS_VALUE',
                [
                    'name' => $this->settings->getFieldName('PREVIOUS_VALUE'),
                    'type' => FieldAdapter::TEXTAREA,
                    'default' => true,
                ]
            ),
            'CURRENT_VALUE' => $this->createField(
                'CURRENT_VALUE',
                [
                    'name' => $this->settings->getFieldName('CURRENT_VALUE'),
                    'type' => FieldAdapter::TEXTAREA,
                    'default' => true,
                ]
            ),
            'CHANGED_AT' => $this->createField(
                'CHANGED_AT',
                [
                    'name' => $this->settings->getFieldName('CHANGED_AT'),
                    'type' => FieldAdapter::DATE,
                    'default' => true,
                ]
            ),
        ];
    }
}