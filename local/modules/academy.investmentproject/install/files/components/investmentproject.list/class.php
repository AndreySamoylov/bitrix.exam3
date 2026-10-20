<?php


use Academy\InvestmentProject\Filter;
use Academy\InvestmentProject\History\Service as HistoryService;
use Academy\InvestmentProject\Integration\Intranet\Employee\Collection as EmployeeCollection;
use Academy\InvestmentProject\Integration\Intranet\Employee\Service as EmployeeService;
use Academy\InvestmentProject\Integration\UI\FieldNameProvider;
use Academy\InvestmentProject\Integration\UI\Filter\ProjectDataProvider;
use Academy\InvestmentProject\Integration\UI\Filter\ProjectSettings;
use Academy\InvestmentProject\Integration\UI\PageNavigationFactory;
use Academy\InvestmentProject\Integration\UI\ValueFormatter;
use Academy\InvestmentProject\Project;
use Academy\InvestmentProject\Service as ProjectService;
use Academy\InvestmentProject\ServiceException;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Context;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Main\Engine\Response\AjaxJson;
use Bitrix\Main\Error as BitrixError;
use Bitrix\Main\ErrorCollection;
use Bitrix\Main\Filter\Field;
use Bitrix\Main\Filter\Filter as FilterService;
use Bitrix\Main\Grid\Options as GridService;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\UI\Buttons\CreateButton;

defined('B_PROLOG_INCLUDED') || die;

final class InvestmentProjectListComponent extends CBitrixComponent implements Controllerable
{
    private const GRID_ID = 'investment-project-grid';

    private readonly ProjectService $projectService;
    private readonly EmployeeService $employeeService;
    private readonly ValueFormatter $valueFormatter;
    private readonly PageNavigationFactory $pageNavigationFactory;

    public function __construct(
        ?CBitrixComponent $component = null,
        ?ProjectService $projectService = null,
        ?EmployeeService $employeeService = null,
        ?ValueFormatter $valueFormatter = null,
        ?PageNavigationFactory $pageNavigationFactory = null
    ) {
        parent::__construct($component);

        Loader::requireModule('academy.investmentproject');

        $this->projectService = $projectService ?? new ProjectService(new HistoryService(), CurrentUser::get());
        $this->employeeService = $employeeService ?? new EmployeeService(Context::getCurrent()->getCulture());
        $this->valueFormatter = $valueFormatter ?? new ValueFormatter();
        $this->pageNavigationFactory = $pageNavigationFactory ?? new PageNavigationFactory();
    }

    public function executeComponent(): void
    {
        $projectDataProvider = $this->getDataProvider();

        $gridService = new GridService($projectDataProvider->getID());
        $filterService = new FilterService($projectDataProvider->getID(), $projectDataProvider);

        $fields = $filterService->getValue();

        $filter = new Filter(
            $fields['TITLE'] ?? null,
            $fields['CREATED_AT'] ?? null,
            $fields['RESPONSIBLE_ID'] ?? null,
            $fields['ESTIMATED_COMPLETION_DATE'] ?? null,
            $fields['COMPLETION_DATE'] ?? null,
        );
        $sort = $gridService->getSorting(['sort' => ['ID' => 'DESC']]);

        $navigationParameters = $gridService->GetNavParams();
        $navigation = $this->pageNavigationFactory->create(
            $navigationParameters['nPageSize'],
            $this->projectService->count($filter)
        );

        $fragment = $this->projectService->getFragment(
            $filter,
            $sort['sort'],
            $navigation->getPageSize(),
            $navigation->getCurrentPage()
        );

        $visibleColumns = $gridService->GetVisibleColumns();
        if (empty($visibleColumns)) {
            $visibleColumns = $filterService->getDefaultFieldIDs();
            $gridService->SetVisibleColumns($visibleColumns);
        }

        $isResponsibleVisible = in_array('RESPONSIBLE_ID', $visibleColumns, true);
        $isCreatedByVisible = in_array('CREATED_BY', $visibleColumns, true);
        $isUpdatedByVisible = in_array('UPDATED_BY', $visibleColumns, true);

        $ids = [];
        foreach ($fragment as $project) {
            if ($isResponsibleVisible) {
                $ids[] = $project->responsibleId;
            }
            if ($isCreatedByVisible) {
                $ids[] = $project->createdBy;
            }
            if ($isUpdatedByVisible) {
                $ids[] = $project->updatedBy;
            }
        }

        $employees = $this->employeeService->getByIds(...array_unique($ids));

        $this->arResult = [
            'grid' => [
                'GRID_ID' => $gridService->getId(),
                'COLUMNS' => array_map(
                    static fn(Field $field): array => [
                        'id' => $field->getId(),
                        'type' => $field->getType(),
                        'name' => $field->getName(),
                        'default' => $field->isDefault(),
                        'sort' => $projectDataProvider->getFieldSortingName($field),
                    ],
                    $filterService->getFields()
                ),
                'ROWS' => $fragment->map(fn(Project $item): array => [
                    'id' => $item->id,
                    'actions' => $this->prepareRowActions($item),
                    'data' => $this->prepareRowData($item, $visibleColumns, $employees)
                ]),
                'NAV_OBJECT' => $navigation,
                'AJAX_MODE' => 'Y',
                'AJAX_OPTION_HISTORY' => 'N',
                'TOTAL_ROWS_COUNT' => $navigation->getRecordCount(),
                'SHOW_PAGESIZE' => true,
                'PAGE_SIZES' => $navigation->getPageSizes()
            ],
            'gridManager' => [
                'gridId' => $gridService->getId(),
                'componentName' => $this->getName(),
                'deleteProjectAction' => 'deleteProject'
            ],
            'filter' => [
                'FILTER' => $filterService->getFieldArrays([
                    'TITLE',
                    'RESPONSIBLE_ID',
                    'ESTIMATED_COMPLETION_DATE',
                    'COMPLETION_DATE',
                    'CREATED_BY',
                    'CREATED_AT',
                    'UPDATED_BY',
                    'UPDATED_AT'
                ]),
                'FILTER_ID' => $filterService->getID(),
                'GRID_ID' => $filterService->getID(),
                'ENABLE_LABEL' => true,
                'DISABLE_SEARCH' => true
            ],
            'toolbar' => [
                'buttons' => [
                    new CreateButton([
                        'link' => CComponentEngine::makePathFromTemplate(
                            $this->arParams['DETAIL_PAGE_URL'],
                            ['INVESTMENT_PROJECT_ID' => 0]
                        )
                    ])
                ]
            ],
        ];
        $this->includeComponentTemplate();
    }

    private function getDataProvider(): ProjectDataProvider
    {
        try {
            return new ProjectDataProvider(
                new ProjectSettings(InvestmentProjectListComponent::GRID_ID, new FieldNameProvider())
            );
        } catch (ArgumentException $e) {
            // Never happens.
            throw new RuntimeException($e->getMessage(), previous: $e);
        }
    }

    private function prepareRowActions(Project $project): array
    {
        return [
            [
                'text' => Loc::getMessage('INVESTMENT_PROJECT_LIST_VIEW_BUTTON_LABEL'),
                'default' => false,
                'href' => CComponentEngine::makePathFromTemplate(
                    $this->arParams['DETAIL_PAGE_URL'],
                    ['INVESTMENT_PROJECT_ID' => $project->id]
                )
            ],
            [
                'text' => Loc::getMessage('INVESTMENT_PROJECT_LIST_HISTORY_BUTTON_LABEL'),
                'default' => false,
                'href' => CComponentEngine::makePathFromTemplate(
                    $this->arParams['HISTORY_PAGE_URL'],
                    ['INVESTMENT_PROJECT_ID' => $project->id]
                )
            ],
            [
                'text' => Loc::getMessage('INVESTMENT_PROJECT_LIST_DELETE_BUTTON_LABEL'),
                'default' => false,
                'onclick' => "BX.Academy.InvestmentProject.Grid.Manager.getInstance().deleteProject({$project->id})"
            ]
        ];
    }

    private function prepareRowData(Project $project, array $visibleColumns, EmployeeCollection $employees): array
    {
        $row = [];
        foreach ($visibleColumns as $column) {
            $row[$column] = match ($column) {
                'ID' => $project->id,
                'RESPONSIBLE_ID' => $this->valueFormatter->formatEmployee($employees->get($project->responsibleId)),
                'TITLE' => $this->valueFormatter->formatProject($project, $this->arParams['DETAIL_PAGE_URL']),
                'DESCRIPTION' => $project->description,
                'COMMENT' => $project->comment,
                'CREATED_AT' => $project->createdAt,
                'CREATED_BY' => $this->valueFormatter->formatEmployee($employees->get($project->createdBy)),
                'UPDATED_AT' => $project->updatedAt,
                'UPDATED_BY' => $this->valueFormatter->formatEmployee($employees->get($project->updatedBy)),
                'COMPLETION_DATE' => $project->completionDate,
                'ESTIMATED_COMPLETION_DATE' => $project->estimatedCompletionDate,
                'INCOME' => $project->income,
            };
        }

        return $row;
    }

    public function deleteProjectAction(int $projectId): AjaxJson
    {
        $errorCollection = new ErrorCollection();
        $result = [];

        try {
            $project = $this->projectService->getById($projectId);
            $this->projectService->delete($project);

            $result['title'] = $project->title;
        } catch (ServiceException $e) {
            $errorCollection->add([
                new BitrixError('Не удалось удалить проект.'),
                BitrixError::createFromThrowable($e)
            ]);
        }

        return new AjaxJson(
            $result,
            $errorCollection->isEmpty() ? AjaxJson::STATUS_SUCCESS : AjaxJson::STATUS_ERROR,
            $errorCollection
        );
    }

    public function configureActions(): array
    {
        return [];
    }
}