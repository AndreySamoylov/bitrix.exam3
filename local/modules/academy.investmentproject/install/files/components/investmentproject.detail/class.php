<?php


use Academy\InvestmentProject\History\Service as HistoryService;
use Academy\InvestmentProject\Integration\Intranet\Employee\Service as EmployeeService;
use Academy\InvestmentProject\Integration\UI\EntityEditor\ProjectProvider;
use Academy\InvestmentProject\Integration\UI\FieldNameProvider;
use Academy\InvestmentProject\Project;
use Academy\InvestmentProject\Service as ProjectService;
use Academy\InvestmentProject\ServiceException;
use Bitrix\Main\Application;
use Bitrix\Main\Context;
use Bitrix\Main\Diag\ExceptionHandler;
use Bitrix\Main\Diag\ExceptionHandlerLog;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Main\Engine\Response\AjaxJson;
use Bitrix\Main\Error as BitrixError;
use Bitrix\Main\Errorable;
use Bitrix\Main\ErrorableImplementation;
use Bitrix\Main\ErrorCollection;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ObjectException;
use Bitrix\Main\Type\DateTime as BitrixDateTime;
use Bitrix\UI\Buttons\Button;
use Bitrix\UI\Buttons\Color;
use Bitrix\UI\Buttons\Icon;

final class InvestmentProjectDetailComponent extends CBitrixComponent implements Errorable, Controllerable
{
    use ErrorableImplementation;

    private readonly ProjectService $projectService;
    private readonly FieldNameProvider $fieldNameProvider;
    private readonly EmployeeService $employeeService;
    private readonly ExceptionHandler $exceptionHandler;

    private ?int $entityId = null;

    /**
     * @throws LoaderException
     */
    public function __construct(
        ?CBitrixComponent $component = null,
        ?ProjectService $projectService = null,
        ?FieldNameProvider $fieldNameProvider = null,
        ?EmployeeService $employeeService = null,
        ?ExceptionHandler $exceptionHandler = null
    ) {
        parent::__construct($component);

        Loader::requireModule('academy.investmentproject');

        $this->projectService = $projectService ?? new ProjectService(
            new HistoryService(),
            CurrentUser::get()
        );
        $this->fieldNameProvider = $fieldNameProvider ?? new FieldNameProvider();
        $this->employeeService = $employeeService ?? new EmployeeService(Context::getCurrent()->getCulture());
        $this->exceptionHandler = $exceptionHandler ?? Application::getInstance()->getExceptionHandler();

        $this->errorCollection = new ErrorCollection();
    }

    public function onPrepareComponentParams($arParams): array
    {
        if (!isset($arParams['INVESTMENT_PROJECT_ID'])) {
            $this->errorCollection->setError(
                new BitrixError(Loc::getMessage('INVESTMENT_PROJECT_NOT_FOUND_ERROR'))
            );
            return $arParams;
        }

        $projectId = (int)$arParams['INVESTMENT_PROJECT_ID'];
        if ($projectId < 0) {
            $this->errorCollection->setError(
                new BitrixError(Loc::getMessage('INVESTMENT_PROJECT_NOT_FOUND_ERROR'))
            );
            return $arParams;
        }

        $this->entityId = $projectId;
        return $arParams;
    }

    public function executeComponent(): void
    {
        if ($this->hasErrors()) {
            $this->displayErrors();
            return;
        }

        $item = !empty($this->entityId) ? $this->projectService->getById($this->entityId) : null;
        $provider = new ProjectProvider($item, $this->fieldNameProvider, $this->employeeService);

        $toolbarButtons = [];
        if (isset($item)) {
            $toolbarButtons[] = new Button([
                'text' => Loc::getMessage('INVESTMENT_PROJECT_HISTORY_BUTTON_LABEL'),
                'link' => CComponentEngine::makePathFromTemplate(
                    $this->arParams['HISTORY_PAGE_URL'],
                    ['INVESTMENT_PROJECT_ID' => $item->id]
                ),
                'icon' => Icon::LIST,
                'color' => Color::LIGHT_BORDER
            ]);
        }

        $this->arResult = [
            'form' => array_merge(
                $provider->getFields(),
                [
                    'COMPONENT_AJAX_DATA' => [
                        'COMPONENT_NAME' => $this->getName(),
                        'SIGNED_PARAMETERS' => $this->getSignedParameters()
                    ],
                    'ENABLE_CONFIG_CONTROL' => false
                ]
            ),
            'title' => $provider->getEntityTitle(),
            'toolbar' => [
                'buttons' => $toolbarButtons
            ]
        ];
        $this->includeComponentTemplate();
    }

    private function displayErrors(): void
    {
        foreach ($this->getErrors() as $error) {
            ShowError($error->getMessage());
        }
    }

    public function saveAction(array $data): AjaxJson
    {
        if (!empty($data['ESTIMATED_COMPLETION_DATE'])) {
            try {
                $estimatedCompletionDate = new BitrixDateTime($data['ESTIMATED_COMPLETION_DATE']);
            } catch (ObjectException) {
                $this->errorCollection->setError(
                    new BitrixError(
                        Loc::getMessage('INVESTMENT_PROJECT_INVALID_ESTIMATED_COMPLETION_DATE_FORMAT')
                    )
                );
            }
        }

        if (!empty($data['COMPLETION_DATE'])) {
            try {
                $completionDate = new BitrixDateTime($data['COMPLETION_DATE']);
            } catch (ObjectException) {
                $this->errorCollection->setError(
                    new BitrixError(
                        Loc::getMessage('INVESTMENT_PROJECT_INVALID_COMPLETION_DATE_FORMAT')
                    )
                );
            }
        }

        if ($this->hasErrors()) {
            return AjaxJson::createError($this->errorCollection);
        }

        try {
            if (!empty($this->arParams['INVESTMENT_PROJECT_ID'])) {
                $item = $this->projectService->getById((int)$this->arParams['INVESTMENT_PROJECT_ID']);

                $item->estimatedCompletionDate = $estimatedCompletionDate ?? $item->estimatedCompletionDate;
                $item->completionDate = $completionDate ?? $item->completionDate;
                $item->title = $data['TITLE'] ?? $item->title;
                $item->description = $data['DESCRIPTION'] ?? $item->description;
                $item->comment = $data['COMMENT'] ?? $item->comment;
                $item->income = $data['INCOME'] ?? $item->income;
                $item->responsibleId = $data['RESPONSIBLE_ID'] ?? $item->responsibleId;

                $this->projectService->update($item);
            } else {
                $item = new Project(
                    $data['ID'] ?? null,
                    $data['TITLE'],
                    null,
                    null,
                    null,
                    null,
                    $estimatedCompletionDate ?? null,
                    $completionDate ?? null,
                    $data['DESCRIPTION'],
                    $data['RESPONSIBLE_ID'],
                    $data['COMMENT'],
                    $data['INCOME']
                );

                $item = $this->projectService->create($item);
            }

            return AjaxJson::createSuccess([
                'ENTITY_ID' => $item->id,
                'REDIRECT_URL' => CComponentEngine::makePathFromTemplate(
                    $this->arParams['DETAIL_PAGE_URL'],
                    ['INVESTMENT_PROJECT_ID' => $item->id]
                )
            ]);
        } catch (ServiceException $e) {
            $this->errorCollection->setError(
                new BitrixError(
                    Loc::getMessage('INVESTMENT_PROJECT_PROCESS_PROJECT_ERROR')
                )
            );
            $this->exceptionHandler->writeToLog($e, ExceptionHandlerLog::CAUGHT_EXCEPTION);
            return AjaxJson::createError($this->errorCollection);
        }
    }

    public function configureActions(): array
    {
        return [];
    }

    protected function listKeysSignedParameters(): array
    {
        return ['INVESTMENT_PROJECT_ID', 'DETAIL_PAGE_URL'];
    }
}