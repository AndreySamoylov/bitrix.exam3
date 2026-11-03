<?php B_PROLOG_INCLUDED === true || die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Bitrix\Main\Type\DateTime;

use Bitrix\Main\Error;
use Bitrix\Main\Errorable;
use Bitrix\Main\ErrorCollection;
use Bitrix\Main\ErrorableImplementation;

use Bitrix\Main\Grid;
use Bitrix\Main\UI\Filter;
use Bitrix\Main\Ui\PageNavigation;

use Exam31\Ticket\Ex3120SomeElementTable;

class ExamElementsListComponent extends CBitrixComponent implements Errorable
{
	use ErrorableImplementation;
	protected const DEFAULT_PAGE_SIZE = 20;
	protected const GRID_ID = 'EXAM31_GRID_ELEMENT';

    protected int $count = 0;
    protected PageNavigation $navObject;

	public function __construct($component = null)
	{
		parent::__construct($component);
		$this->errorCollection = new ErrorCollection();
	}

	public function onPrepareComponentParams($arParams): array
	{
		if (!Loader::includeModule('exam31.ticket'))
		{
			$this->errorCollection->setError(
				new Error(Loc::getMessage('EXAM31_TICKET_MODULE_NOT_INSTALLED'))
			);
			return $arParams;
		}

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

		$this->arResult['filter'] = $this->getFilter();
        $this->count = $this->getSomeElementsCount();
        $this->navObject = $this->getNavObject();
        $this->arResult['ITEMS'] = $this->getSomeElementList();
		$this->arResult['grid'] = $this->prepareGrid($this->arResult['ITEMS']);

		$this->includeComponentTemplate();

		global $APPLICATION;
		$APPLICATION->SetTitle(Loc::getMessage('EXAM31_ELEMENTS_LIST_PAGE_TITLE'));
	}

    protected function getNavObject(): PageNavigation
    {
        $gridOptions = new Grid\Options(self::GRID_ID);
        $navParams = $gridOptions->getNavParams([
            'nPageSize' => self::DEFAULT_PAGE_SIZE
        ]);

        $nav = new PageNavigation(self::GRID_ID);

        $nav
            ->allowAllRecords(true)
            ->setPageSize((int)$navParams['nPageSize'])
            ->initFromUri()
        ;

        $nav->setRecordCount($this->count);

        return $nav;
    }

    protected function getFilter(): array
    {
        return [
            'FILTER_ID' => self::GRID_ID,
            'GRID_ID' => self::GRID_ID,
            'FILTER' => [
                ['id' => 'TITLE', 'name' => Loc::getMessage('EXAM31_ELEMENTS_LIST_FILTER_TITLE_NAME'), 'type' => 'string'],
            ],
            'ENABLE_LIVE_SEARCH' => true,
            'ENABLE_LABEL' => true
        ];
    }

    protected function getSomeElementsCount(): int
    {
        $filterOptions = new  Filter\Options(self::GRID_ID);
        $options = $filterOptions->getFilter();

        $itemsQuery = Ex3120SomeElementTable::query()
            ->addSelect('ID')
        ;

        if (!empty($options['TITLE'])) {
            $itemsQuery->whereLike('TITLE', '%' . $options['TITLE'] . '%');
        }

        return (int) $itemsQuery->queryCountTotal() ?: 0;
    }

	protected function getSomeElementList(): array
	{
        $filterOptions = new  Filter\Options(self::GRID_ID);
        $options = $filterOptions->getFilter();

        $itemsQuery = Ex3120SomeElementTable::query()
            ->addSelect('*')
        ;

        if (!empty($options['TITLE'])) {
            $itemsQuery->whereLike('TITLE', '%' . $options['TITLE'] . '%');
        }

        $items = $itemsQuery
            ->setLimit($this->navObject->getLimit())
            ->setOffset($this->navObject->getOffset())
            ->fetchAll();

		$preparedItems = [];
		foreach ($items as $item)
		{
			$item['DETAIL_URL'] = $this->getDetailPageUrl($item['ID']);
			$item['INFO_URL'] = $this->getInfoPageUrl($item['ID']);
			$item['DATE_MODIFY'] = $item['DATE_MODIFY'] instanceof DateTime
				? $item['DATE_MODIFY']->toString()
				: null;

			$preparedItems[] = $item;
		}
		return $preparedItems;
	}

	protected function prepareGrid($items): array
	{
		return [
			'GRID_ID' => static::GRID_ID,
			'COLUMNS' => $this->getGridColums(),
			'ROWS' => $this->getGridRows($items),
			'TOTAL_ROWS_COUNT' => $this->count,
			'NAV_OBJECT' => $this->navObject,
			'SHOW_PAGINATION' => true,
			'SHOW_ROW_CHECKBOXES' => false,
			'SHOW_SELECTED_COUNTER' => false,
			'AJAX_MODE' => 'Y',
			'AJAX_OPTION_JUMP' => 'N',
			'AJAX_OPTION_HISTORY' => 'N',
		];
	}

	protected function getGridColums(): array
	{
		$fieldsLabel = Ex3120SomeElementTable::getFieldsDisplayLabel();
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
					'INFO' => $this->getInfoHTMLLink($item["INFO_URL"]),
				]
			];
		}
		return $rows;
	}

	protected function getDetailPageUrl(int $id): string
	{
		return str_replace('#ELEMENT_ID#', $id, $this->arParams['DETAIL_PAGE_URL']);
	}

    protected function getInfoPageUrl(int $id): string
    {
        return str_replace('#ELEMENT_ID#', $id, $this->arParams['INFO_PAGE_URL']);
    }
	protected function getDetailHTMLLink(string $detail_url): string
	{
		return "<a href=\"" . $detail_url . "\">" . Loc::getMessage('EXAM31_ELEMENTS_LIST_GRIG_COLUMN_DETAIL_NAME') . "</a>";
	}
    protected function getInfoHTMLLink(string $info_url): string
    {
        return "<a href=\"" . $info_url . "\">" . Loc::getMessage('EXAM31_ELEMENTS_LIST_GRIG_COLUMN_INFO_NAME') . "</a>";
    }
}