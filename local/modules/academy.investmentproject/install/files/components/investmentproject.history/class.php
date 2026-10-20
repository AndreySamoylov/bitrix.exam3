<?php

use Academy\InvestmentProject\Collection as ProjectCollection;
use Academy\InvestmentProject\History\Filter;
use Academy\InvestmentProject\History\Entry as HistoryItem;
use Academy\InvestmentProject\History\Service as HistoryService;
use Academy\InvestmentProject\Integration\Intranet\Employee\Collection as EmployeeCollection;
use Academy\InvestmentProject\Integration\Intranet\Employee\Service as EmployeeService;
use Academy\InvestmentProject\Integration\UI\FieldNameProvider;
use Academy\InvestmentProject\Integration\UI\Filter\HistoryDataProvider;
use Academy\InvestmentProject\Integration\UI\Filter\HistorySettings;
use Academy\InvestmentProject\Integration\UI\Filter\ProjectDataProvider;
use Academy\InvestmentProject\Integration\UI\Filter\ProjectSettings;
use Academy\InvestmentProject\Integration\UI\PageNavigationFactory;
use Academy\InvestmentProject\Integration\UI\ValueFormatter;
use Academy\InvestmentProject\Service as ProjectService;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Context;
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Main\Filter\Field;
use Bitrix\Main\Filter\Filter as FilterService;
use Bitrix\Main\Grid\Options as GridService;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Localization\Loc;

defined('B_PROLOG_INCLUDED') || die;

final class InvestmentProjectHistoryComponent extends CBitrixComponent
{
    private const GRID_ID = 'investment-project-history-grid';

    private readonly HistoryService $historyService;
    private readonly ProjectService $projectService;
    private readonly EmployeeService $employeeService;
    private readonly ValueFormatter $valueFormatter;
    private readonly PageNavigationFactory $pageNavigationFactory;
    private readonly FieldNameProvider $fieldNameProvider;

    private ?int $projectId = null;

    /**
     * @throws LoaderException
     */
    public function __construct(
        ?CBitrixComponent $component = null,
        ?ProjectService $projectService = null,
        ?HistoryService $historyService = null,
        ?EmployeeService $employeeService = null,
        ?ValueFormatter $valueFormatter = null,
        ?PageNavigationFactory $pageNavigationFactory = null,
        ?FieldNameProvider $fieldNameProvider = null
    ) {
        parent::__construct($component);

        Loader::requireModule('academy.investmentproject');

        $this->historyService = $historyService ?? new HistoryService();
        $this->projectService = $projectService ?? new ProjectService($this->historyService, CurrentUser::get());
        $this->employeeService = $employeeService ?? new EmployeeService(Context::getCurrent()->getCulture());
        $this->valueFormatter = $valueFormatter ?? new ValueFormatter();
        $this->pageNavigationFactory = $pageNavigationFactory ?? new PageNavigationFactory();
        $this->fieldNameProvider = $fieldNameProvider ?? new FieldNameProvider();
    }

    public function onPrepareComponentParams($arParams): array
    {
        if (!isset($arParams['INVESTMENT_PROJECT_ID'])) {
            return $arParams;
        }

        $projectId = (int)$arParams['INVESTMENT_PROJECT_ID'];
        if ($projectId < 0) {
            return $arParams;
        }

        $this->projectId = $projectId;
        return $arParams;
    }

    public function executeComponent(): void
    {
        $gridService = new GridService(InvestmentProjectHistoryComponent::GRID_ID);

        try {
            $historyDataProvider = new HistoryDataProvider(
                new ProjectDataProvider(new ProjectSettings($gridService->getId(), $this->fieldNameProvider)),
                new HistorySettings($gridService->getId(), $this->fieldNameProvider)
            );
            $filterService = new FilterService(
                $gridService->getId(),
                $historyDataProvider
            );
        } catch (ArgumentException $e) {
            throw new RuntimeException($e->getMessage(), previous: $e);
        }

        $fields = $filterService->getValue();

        $filter = new Filter(
            $this->projectId ?? $fields['PROJECT_ID'],
            $fields['FIELD_NAME'] ?? null,
            $fields['PREVIOUS_VALUE'] ?? null,
            $fields['CURRENT_VALUE'] ?? null,
            $fields['CHANGED_AT'] ?? null,
            (array)($fields['AUTHOR_ID'] ?? null),
        );

        $navigationParameters = $gridService->GetNavParams();
        $count = $this->historyService->count($filter);

        $navigation = $this->pageNavigationFactory->create($navigationParameters['nPageSize'], $count);
        $sort = $gridService->getSorting(['sort' => ['CHANGED_AT' => 'DESC', 'ID' => 'DESC']]);

        $fragment = $this->historyService->getFragment(
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

        $projectIds = $userIds = [];
        foreach ($fragment as $item) {
            $projectIds[] = $item->projectId;
            $userIds[] = $item->authorId;
        }

        $projects = $this->projectService->getByIds(...array_unique($projectIds));
        $employees = $this->employeeService->getByIds(...array_unique($userIds));

        $filterFieldMask = [
            'FIELD_NAME',
            'AUTHOR_ID',
            'PREVIOUS_VALUE',
            'CURRENT_VALUE',
            'CHANGED_AT'
        ];

        $this->arResult = [
            'grid' => [
                'GRID_ID' => $gridService->getId(),
                'COLUMNS' => array_map(
                    static fn(Field $field): array => [
                        'id' => $field->getId(),
                        'type' => $field->getType(),
                        'name' => $field->getName(),
                        'default' => $field->isDefault(),
                        'sort' => $historyDataProvider->getFieldSortingName($field),
                    ],
                    $filterService->getFields()
                ),
                'ROWS' => $fragment->map(fn(HistoryItem $history): array => [
                    'id' => $history->id,
                    'data' => $this->prepareRowData(
                        $history,
                        $visibleColumns,
                        $projects,
                        $employees,
                    )
                ]),
                'NAV_OBJECT' => $navigation,
                'TOTAL_ROWS_COUNT' => $count,
                'SHOW_PAGESIZE' => true,
                'PAGE_SIZES' => $navigation->getPageSizes(),
                'SHOW_ROW_CHECKBOXES' => false,
                'SHOW_ROW_ACTIONS_MENU' => false,
                'SHOW_CHECK_ALL_CHECKBOXES' => false,
                'SHOW_SELECTED_COUNTER' => false,
                'AJAX_MODE' => 'Y',
                'AJAX_OPTION_HISTORY' => 'N',
                'AJAX_OPTION_JUMP' => 'N'
            ],
            'filter' => [
                'FILTER_ID' => $filterService->getId(),
                'GRID_ID' => $filterService->getId(),
                'FILTER' => $filterService->getFieldArrays($filterFieldMask),
                'ENABLE_LABEL' => true,
                'DISABLE_SEARCH' => true,
                'CONFIG' => [
                    // Автофокусировка включена по умолчанию.
                    // С активной автофокусировкой прерывается анимация открытия слайдера.
                    'AUTOFOCUS' => false,
                ],
            ]
        ];
        $this->includeComponentTemplate();
    }

    private function prepareRowData(
        HistoryItem $item,
        array $visibleColumns,
        ProjectCollection $projects,
        EmployeeCollection $employees,
    ): array {
        $placeholderValue = Loc::getMessage('INVESTMENT_PROJECT_HISTORY_NO_VALUE_PLACEHOLDER');

        $row = [];
        foreach ($visibleColumns as $column) {
            $row[$column] = match ($column) {
                'ID' => $item->id,
                'PROJECT_ID' => $this->valueFormatter->formatProject(
                    $projects->get($item->projectId),
                    $this->arParams['DETAIL_PAGE_URL']
                ),
                'AUTHOR_ID' => $this->valueFormatter->formatEmployee($employees->get($item->authorId)),
                'FIELD_NAME' => $this->fieldNameProvider->getProjectFieldName($item->fieldName),
                'PREVIOUS_VALUE' => strlen($item->previousValue) > 0 ? $item->previousValue : $placeholderValue,
                'CURRENT_VALUE' => strlen($item->currentValue) > 0 ? $item->currentValue : $placeholderValue,
                'CHANGED_AT' => $item->changedAt
            };
        }

        return $row;
    }
}