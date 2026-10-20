<?php

namespace Academy\InvestmentProject\Integration\UI\EntityEditor;

use Academy\InvestmentProject\Integration\Intranet\Employee\Employee;
use Academy\InvestmentProject\Integration\Intranet\Employee\Service as EmployeeService;
use Academy\InvestmentProject\Integration\Intranet\Employee\ServiceException;
use Academy\InvestmentProject\Integration\UI\FieldNameProvider;
use Academy\InvestmentProject\Project;
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Main\Localization\Loc;
use Bitrix\UI\EntityEditor\BaseProvider;

/**
 * Строитель параметров формы компонента bitrix:ui.form.
 */
final class ProjectProvider extends BaseProvider
{
    public function __construct(
        private readonly ?Project $item,
        private readonly FieldNameProvider $fieldNameProvider,
        private readonly EmployeeService $employeeService
    ) {
    }

    /**
     * Системный метод, нерелевантный для текущего урока.
     *
     * @return string
     */
    public function getGUID(): string
    {
        return 'INVESTMENT_PROJECT';
    }

    /**
     * Системный метод, нерелевантный в рамках текущего урока.
     *
     * @return string
     */
    public function getEntityTypeName(): string
    {
        return 'investment_project';
    }

    public function getEntityId(): ?int
    {
        return $this->item?->id;
    }

    /**
     * Подготавливает инфомрацию о всех полях, доступных в карточке детального просмотра проекта.
     * За структуру отображения полей (в каких разделах карточки какие поля находятся) отвечает {@link ProjectProvider::getEntityConfig()}
     *
     * @return array[]
     */
    public function getEntityFields(): array
    {
        $exists = isset($this->item);
        $userProfilePathTemplate = $this->employeeService->getProfileUrlTemplate();

        return [
            [
                'name' => 'ID',
                'title' => $this->fieldNameProvider->getProjectFieldName('ID'),
                'editable' => false,
                'type' => 'hidden',
            ],
            [
                'name' => 'RESPONSIBLE_ID',
                'title' => $this->fieldNameProvider->getProjectFieldName('RESPONSIBLE_ID'),
                'editable' => true,
                'multiple' => false,
                'type' => 'user',
                'data' => [
                    'enableEditInView' => true,
                    'formated' => 'RESPONSIBLE_FORMATTED_NAME',
                    'position' => 'RESPONSIBLE_WORK_POSITION',
                    'photoUrl' => 'RESPONSIBLE_PHOTO_URL',
                    'showUrl' => 'PATH_TO_RESPONSIBLE_USER',
                    'pathToProfile' => $userProfilePathTemplate
                ],
            ],
            [
                'name' => 'TITLE',
                'title' => $this->fieldNameProvider->getProjectFieldName('TITLE'),
                'editable' => true,
                'type' => 'text',
            ],
            [
                'name' => 'DESCRIPTION',
                'title' => $this->fieldNameProvider->getProjectFieldName('DESCRIPTION'),
                'editable' => true,
                'type' => 'textarea',
            ],
            [
                'name' => 'COMMENT',
                'title' => $this->fieldNameProvider->getProjectFieldName('COMMENT'),
                'editable' => true,
                'type' => 'textarea',
            ],
            [
                'name' => 'CREATED_AT',
                'title' => $this->fieldNameProvider->getProjectFieldName('CREATED_AT'),
                'editable' => false,
                'type' => $exists ? 'datetime' : 'hidden',
            ],
            [
                'name' => 'CREATED_BY',
                'title' => $this->fieldNameProvider->getProjectFieldName('CREATED_BY'),
                'editable' => false,
                'type' => $exists ? 'user' : 'hidden',
                'data' => [
                    'enableEditInView' => false,
                    'formated' => 'CREATED_BY_FORMATTED_NAME',
                    'position' => 'CREATED_BY_WORK_POSITION',
                    'photoUrl' => 'CREATED_BY_PHOTO_URL',
                    'showUrl' => 'PATH_TO_CREATED_BY_USER',
                    'pathToProfile' => $userProfilePathTemplate
                ],
            ],
            [
                'name' => 'UPDATED_AT',
                'title' => $this->fieldNameProvider->getProjectFieldName('UPDATED_AT'),
                'editable' => false,
                'type' => $exists ? 'datetime' : 'hidden',
            ],
            [
                'name' => 'UPDATED_BY',
                'title' => $this->fieldNameProvider->getProjectFieldName('UPDATED_BY'),
                'editable' => false,
                'type' => $exists ? 'user' : 'hidden',
                'data' => [
                    'enableEditInView' => false,
                    'formated' => 'UPDATED_BY_FORMATTED_NAME',
                    'position' => 'UPDATED_BY_WORK_POSITION',
                    'photoUrl' => 'UPDATED_BY_PHOTO_URL',
                    'showUrl' => 'PATH_TO_UPDATED_BY_USER',
                    'pathToProfile' => $userProfilePathTemplate
                ],
            ],
            [
                'name' => 'COMPLETION_DATE',
                'title' => $this->fieldNameProvider->getProjectFieldName('COMPLETION_DATE'),
                'editable' => true,
                'type' => 'datetime',
            ],
            [
                'name' => 'ESTIMATED_COMPLETION_DATE',
                'title' => $this->fieldNameProvider->getProjectFieldName('ESTIMATED_COMPLETION_DATE'),
                'editable' => true,
                'type' => 'datetime',
            ],
            [
                'name' => 'INCOME',
                'title' => $this->fieldNameProvider->getProjectFieldName('INCOME'),
                'editable' => true,
                'type' => 'text',
            ],
        ];
    }

    /**
     * Добавляет системные параметры проекта только для существующих проектов.
     *
     * @return array[]
     */
    public function getEntityConfig(): array
    {
        $config = [
            [
                'type' => 'column',
                'name' => 'default_column',
                'elements' => [
                    [
                        'name' => 'main',
                        'title' => Loc::getMessage('INVESTMENT_PROJECT_MAIN_SECTION_TITLE'),
                        'type' => 'section',
                        'elements' => [
                            ['name' => 'ID'],
                            ['name' => 'RESPONSIBLE_ID'],
                            ['name' => 'TITLE'],
                            ['name' => 'DESCRIPTION'],
                            ['name' => 'COMMENT'],
                            ['name' => 'COMPLETION_DATE'],
                            ['name' => 'ESTIMATED_COMPLETION_DATE'],
                            ['name' => 'INCOME'],
                        ]
                    ],

                ]
            ]
        ];

        if (isset($this->item)) {
            $config[] = [
                'name' => 'additional',
                'title' => Loc::getMessage('INVESTMENT_PROJECT_ADDITIONAL_SECTION_TITLE'),
                'type' => 'section',
                'editable' => false,
                'elements' => [
                    ['name' => 'CREATED_AT'],
                    ['name' => 'CREATED_BY'],
                    ['name' => 'UPDATED_AT'],
                    ['name' => 'UPDATED_BY'],
                ],
                'data' => [
                    'enableToggling' => false
                ]
            ];
        }

        return $config;
    }

    /**
     * Возвращает информацию о проекте. Для проекта в режиме создания возвращает предзаполненные поля.
     * Предзаполнить можно только ответственного по проекту, это текущий пользователь.
     *
     * @throws ServiceException
     */
    public function getEntityData(): array
    {
        if (!isset($this->item)) {
            $responsibleId = CurrentUser::get()->getId();

            $responsible = $this->employeeService->getById($responsibleId);
            return array_merge(
                [
                    'RESPONSIBLE_ID' => $responsibleId,
                ],
                $this->prepareEmployeeInfo($responsible, 'RESPONSIBLE')
            );
        }

        $employees = $this->employeeService->getByIds(
            $this->item->responsibleId,
            $this->item->createdBy,
            $this->item->updatedBy
        );
        return array_merge(
            [
                'ID' => $this->item->id,
                'RESPONSIBLE_ID' => $this->item->responsibleId,
                'TITLE' => $this->item->title,
                'DESCRIPTION' => $this->item->description,
                'COMMENT' => $this->item->comment,
                'CREATED_AT' => $this->item->createdAt->toString(),
                'CREATED_BY' => $this->item->createdBy,
                'UPDATED_AT' => $this->item->updatedAt->toString(),
                'UPDATED_BY' => $this->item->updatedBy,
                'COMPLETION_DATE' => $this->item->completionDate?->toString(),
                'ESTIMATED_COMPLETION_DATE' => $this->item->estimatedCompletionDate->toString(),
                'INCOME' => $this->item->income
            ],
            $this->prepareEmployeeInfo($employees->get($this->item->responsibleId), 'RESPONSIBLE'),
            $this->prepareEmployeeInfo($employees->get($this->item->createdBy), 'CREATED_BY'),
            $this->prepareEmployeeInfo($employees->get($this->item->updatedBy), 'UPDATED_BY'),
        );
    }

    /**
     * Подготавливает дополнительную системную информацию для поля типа "user" в детальной карточке проекта.
     */
    private function prepareEmployeeInfo(Employee $employee, string $fieldName): array
    {
        return [
            "{$fieldName}_FORMATTED_NAME" => $employee->formattedName,
            "{$fieldName}_WORK_POSITION" => $employee->workPosition,
            "{$fieldName}_PHOTO_URL" => $employee->personalPhotoPath,
            "PATH_TO_{$fieldName}_USER" => $employee->profileUrl,
        ];
    }

    /**
     * Определяет заголовок страницы, а не значение поля "название" проекта.
     *
     * @return string
     */
    public function getEntityTitle(): string
    {
        return $this->item?->title ?? Loc::getMessage('INVESTMENT_PROJECT_DEFAULT_ENTITY_TITLE');
    }
}