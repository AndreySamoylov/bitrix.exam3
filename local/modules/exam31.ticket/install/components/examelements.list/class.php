<?php B_PROLOG_INCLUDED === true || die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\Text\HtmlFilter;

use Bitrix\Main\Error;
use Bitrix\Main\Errorable;
use Bitrix\Main\ErrorCollection;
use Bitrix\Main\ErrorableImplementation;

use Bitrix\Main\UI\Filter;
use Bitrix\Main\Grid\Options as GridOptions;
use Bitrix\Main\UI\PageNavigation;

use Exam31\Ticket\AndreyElementTable;
use Exam31\Ticket\AndreyInfoTable;

use Bitrix\UI\Buttons\CreateButton;

class ExamElementsListComponent extends CBitrixComponent implements Errorable
{
	use ErrorableImplementation;
    protected int $totalElementCount;
    protected PageNavigation $navObject;
	protected const DEFAULT_PAGE_SIZE = 20;
	protected const GRID_ID = 'EXAM31_GRID_ELEMENT';
	protected const FILTER_ID = 'EXAM31_GRID_FILTER';

	public function __construct($component = null)
	{
        if (!Loader::includeModule('exam31.ticket'))
        {
            $this->errorCollection->setError(
                new Error(Loc::getMessage('EXAM31_TICKET_MODULE_NOT_INSTALLED'))
            );
        }

		parent::__construct($component);
		$this->errorCollection = new ErrorCollection();
        $this->totalElementCount = $this->getTotalElementCount();
        $this->navObject = $this->getNavObject();
	}

	public function onPrepareComponentParams($arParams): array
	{
		$arParams['ELEMENT_COUNT'] = (int) $arParams['ELEMENT_COUNT'];
		if ($arParams['ELEMENT_COUNT'] <= 0)
		{
			$arParams['ELEMENT_COUNT'] = static::DEFAULT_PAGE_SIZE;
		}
		return $arParams;
	}

	private function displayErrors(): void
	{
		foreach ($this->getErrors() as $error)
		{
			ShowError($error->getMessage());
		}
	}

	public function executeComponent(): void
	{
		if ($this->hasErrors())
		{
			$this->displayErrors();
			return;
		}

        $this->arResult['filter'] = $this->prepareFilter();
        $this->arResult['ITEMS'] = $this->getSomeElementList();
        $this->arResult['grid'] = $this->prepareGrid($this->arResult['ITEMS']);
        $this->arResult['toolbar'] = [
            'buttons' => [
                new CreateButton([
                    'link' => str_replace('#ELEMENT_ID#', 0, $this->arParams['DETAIL_PAGE_URL']),
                    'text' => 'Добавить',
                ])
            ],
        ];

		$this->includeComponentTemplate();

		global $APPLICATION;
		$APPLICATION->SetTitle(Loc::getMessage('EXAM31_ELEMENTS_LIST_PAGE_TITLE'));
	}

    protected function prepareFilter(): array
    {
        return [
            'FILTER_ID' => self::FILTER_ID,
            'GRID_ID' => self::GRID_ID,
            'FILTER' => [
                [
                    'id' => 'TITLE', 'name' => Loc::getMessage('EXAM31_ELEMENTS_LIST_FILTER_COLUMN_TITLE_NAME')
                ],
            ],
            'ENABLE_LIVE_SEARCH' => true,
            'ENABLE_LABEL' => true
        ];
    }

    protected function getSomeElementList(): array
    {
        $filterOption = new Filter\Options(self::FILTER_ID);
        $filterData = $filterOption->getFilter([]);

        $result = AndreyElementTable::query()
            ->addSelect('*')
            ->addSelect('INFO_COUNT')
            ->addGroup('ID')
            ->registerRuntimeField(
                null,
                new \Bitrix\Main\ORM\Fields\ExpressionField(
                    'INFO_COUNT',
                    'COUNT(%s)',
                    ['INFO.ID']
                )
            )
            ->setLimit($this->navObject->getLimit())
            ->setOffset($this->navObject->getOffset())
        ;

        if (!empty($filterData['TITLE'])) {
            $title = htmlspecialcharsbx($filterData['TITLE']);
            $result->whereLike('TITLE', '%' . $title . '%');
        }

        $items = $result->fetchAll();

        $preparedItems = [];
        foreach ($items as $item) {
            $item['ACTIVE'] = !empty($item['ACTIVE']) && $item['ACTIVE'] === 'Y' ? 'Да' : 'Нет';
            $item['DETAIL_URL'] = $this->getDetailPageUrl($item['ID']);
            $item['INFO_URL'] = $this->getInfoPageUrl($item['ID']);
            $item['DATE_MODIFY'] = $item['DATE_MODIFY'] instanceof DateTime
                ? $item['DATE_MODIFY']->toString()
                : null;

            $preparedItems[] = $item;
        }
        return $preparedItems;
    }

    /**
     * @return int
     */
    protected function getTotalElementCount(): int
    {
        $result = AndreyElementTable::query()
            ->addSelect('CNT')
            ->registerRuntimeField(
                null,
                new \Bitrix\Main\ORM\Fields\ExpressionField(
                    'CNT',
                    'COUNT(%s)',
                    ['ID']
                )
            )
        ;

        $filterOption = new Filter\Options(self::FILTER_ID);
        $filterData = $filterOption->getFilter([]);

        if (!empty($filterData['TITLE'])) {
            $title = htmlspecialcharsbx($filterData['TITLE']);
            $result->whereLike('TITLE', '%' . $title . '%');
        }

        return $result->fetch()['CNT'] ?? '-1';
    }

    /**
     * @param $items
     * @return array
     */
	protected function prepareGrid($items): array
	{
		return [
			'GRID_ID' => static::GRID_ID,
			'COLUMNS' => $this->getGridColums(),
            'ROWS' => $this->getGridRows($items),
            'TOTAL_ROWS_COUNT' => $this->totalElementCount,
            'NAV_OBJECT' => $this->navObject,
            'SHOW_PAGINATION' => true,
            'SHOW_ROW_CHECKBOXES' => false,
			'SHOW_SELECTED_COUNTER' => false,
			'AJAX_MODE' => 'Y',
			'AJAX_OPTION_JUMP' => 'N',
			'AJAX_OPTION_HISTORY' => 'N',
		];
	}

    /**
     * @return array[]
     */
	protected function getGridColums(): array
	{
		$fieldsLabel = AndreyElementTable::getFieldsDisplayLabel();
        return [
			['id' => 'ACTIVE', 'default' => true, 'name' => $fieldsLabel['ACTIVE'] ?? 'ACTIVE'],
			['id' => 'ID', 'default' => true, 'name' => $fieldsLabel['ID'] ?? 'ID'],
			['id' => 'DATE_MODIFY', 'default' => true, 'name' => $fieldsLabel['DATE_MODIFY'] ?? 'DATE_MODIFY'],
			['id' => 'TITLE', 'default' => true, 'name' => $fieldsLabel['TITLE'] ?? 'TITLE'],
			['id' => 'TEXT', 'default' => true, 'name' => $fieldsLabel['TEXT'] ?? 'TEXT'],
			['id' => 'DETAIL', 'default' => true, 'name' => Loc::getMessage('EXAM31_ELEMENTS_LIST_GRIG_COLUMN_DETAIL_NAME')],
			['id' => 'INFO', 'default' => true, 'name' => Loc::getMessage('EXAM31_ELEMENTS_LIST_GRIG_COLUMN_INFO_NAME')],
		];
	}

    /**
     * @param array $items
     * @return array
     */
	protected function getGridRows(array $items): array
	{
		if (empty($items))
		{
			return [];
		}

		$rows = [];
		foreach ($items as $key => $item)
		{
			$rows[$key] = [
				'id' => $item["ID"],
				'columns' => [
					'ID' => $item["ID"],
					'DATE_MODIFY' => $item["DATE_MODIFY"],
					'TITLE' => $item["TITLE"],
					'TEXT' => $item["TEXT"],
					'ACTIVE' => $item["ACTIVE"],
					'DETAIL' => $this->getDetailHTMLLink($item["DETAIL_URL"]),
					'INFO' => $this->getDetailInfoLink($item['INFO_URL'], $item['INFO_COUNT']),
				],
                'actions' => [
                    [
                        'text' => Loc::getMessage('EXAM31_ELEMENTS_LIST_GRIG_COLUMN_DETAIL_NAME'),
                        'default' => false,
                        'href' => $item["DETAIL_URL"],
                    ],
                    [
                        'text' => Loc::getMessage('EXAM31_ELEMENTS_LIST_GRIG_COLUMN_INFO_NAME'),
                        'default' => false,
                        'href' => $item['INFO_URL'],
                    ],
                ]
			];
		}
		return $rows;
	}

    /**
     * @return PageNavigation
     */
    protected function getNavObject(): PageNavigation
    {
        $gridOptions = new GridOptions(static::GRID_ID);

        $navParams = $gridOptions->getNavParams([
            'nPageSize' => static::DEFAULT_PAGE_SIZE,
        ]);

        $nav = new PageNavigation('orders');
        $nav->allowAllRecords(true)
            ->setPageSize((int)$navParams['nPageSize'])
            ->initFromUri()
        ;

        $nav->setRecordCount($this->totalElementCount);

        return $nav;
    }

    /**
     * @param int $id
     * @return string
     */
	protected function getDetailPageUrl(int $id): string
	{
		return str_replace('#ELEMENT_ID#', $id, $this->arParams['DETAIL_PAGE_URL']);
	}

    /**
     * @param int $id
     * @return string
     */
    protected function getInfoPageUrl(int $id): string
    {
        return str_replace('#ELEMENT_ID#', $id, $this->arParams['INFO_PAGE_URL']);
    }

    /**
     * @param string $detail_url
     * @return string
     */
	protected function getDetailHTMLLink(string $detail_url): string
	{
        return "<a href=\"" . $detail_url . "\">" . Loc::getMessage('EXAM31_ELEMENTS_LIST_GRIG_COLUMN_DETAIL_NAME') . "</a>";
	}

    /**
     * @param string $detailUrl
     * @param int $infoCount
     * @return string
     */
    protected function getDetailInfoLink(string $detailUrl, int $infoCount): string
    {
        return sprintf("<a href='%s'>%s %d</a>",
            $detailUrl,
            Loc::getMessage('EXAM31_ELEMENTS_LIST_GRIG_COLUMN_INFO_NAME'),
            $infoCount
        );
    }
}
