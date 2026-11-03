<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Bizproc\Activity\BaseActivity;
use Bitrix\Bizproc\FieldType;
use Bitrix\Main\ErrorCollection;
use Bitrix\Bizproc\Activity\PropertiesDialog;
use Bitrix\Main\Text\HtmlFilter;
use Exam31\Ticket\AndreyElementTable;

class CBPExamTicketActivity extends BaseActivity
{
	public function __construct($name)
	{
		parent::__construct($name);

		$this->arProperties = [
			'ID' => 0,

			//return
			'DATE_MODIFY' => null,
			'ACTIVE' => null,
			'TITLE' => null,
			'TEXT' => null,
		];

		$this->SetPropertiesTypes([
			'DATE_MODIFY' => ['Type' => FieldType::DATETIME],
			'ACTIVE' => ['Type' => FieldType::BOOL],
			'TITLE' => ['Type' => FieldType::STRING],
			'TEXT' => ['Type' => FieldType::TEXT],
		]);

	}

	protected static function getFileName(): string
	{
		return __FILE__;
	}

	protected function internalExecute(): ErrorCollection
	{
		$errors = parent::internalExecute();

		$elementId = (int) $this->preparedProperties["ID"];
        $element = AndreyElementTable::query()
            ->addSelect('*')
            ->where('ID', '=', $elementId)
            ->setLimit(1)
            ->fetch();

		if(empty($element))
		{
            //Если нет данных, отдаем пустые значения
            $this->preparedProperties['ID'] = 0;
            $this->preparedProperties['TITLE'] = '';

            //Пишем в журнал выполнения БП что данные не нашли
            $this->log(
                Loc::getMessage(
                    'EXAM31_TICKET_ACTIVITY_LOG_TEXT_N',
                    [
                        '#ID#' => $elementId,
                    ]
                )
            );
		}
		else
		{
            $this->preparedProperties['DATE_MODIFY'] = HtmlFilter::encode($element['DATE_MODIFY']);
            $this->preparedProperties['ACTIVE'] =  HtmlFilter::encode($element['ACTIVE']);
            $this->preparedProperties['TITLE'] = HtmlFilter::encode($element['TITLE']);
            $this->preparedProperties['TEXT'] = HtmlFilter::encode($element['TEXT']);

            //Значения найдены
            $this->log(
                Loc::getMessage(
                    'EXAM31_TICKET_ACTIVITY_LOG_TEXT_FOUND',
                    [
                        '#ID#' => $elementId,
                    ]
                )
            );
		}

		return $errors;
	}

	public static function getPropertiesDialogMap(?PropertiesDialog $dialog = null): array
	{
		$map = [
			'ID' => [
				'Name' => 'ID',
				'FieldName' => 'ID',
				'Type' => FieldType::INT,
				'Required' => true,
				'Default' => '',
				'Options' => [],
			],
		];
		
		return $map;
	}
}