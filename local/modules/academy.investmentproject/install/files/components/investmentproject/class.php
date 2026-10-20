<?php


defined('B_PROLOG_INCLUDED') || die;


final class InvestmentProjectComponent extends CBitrixComponent
{
    private string $root;
    private string $defaultPage;
    private array $urlTemplates;
    private CComponentEngine $engine;

    public function __construct(?CBitrixComponent $component = null, ?CComponentEngine $engine = null)
    {
        parent::__construct($component);

        $this->engine = $engine ?? new CComponentEngine();
    }

    public function onPrepareComponentParams($arParams): array
    {
        $this->root = $arParams['SEF_FOLDER'];
        $this->defaultPage = $arParams['DEFAULT_PAGE'];
        $this->urlTemplates = $arParams['URL_TEMPLATES'];

        return $arParams;
    }

    public function executeComponent(): void
    {
        $page = $this->engine->guessComponentPath($this->root, $this->urlTemplates, $variables);

        $this->arResult = [
            'VARIABLES' => $variables,
            'DETAIL_PAGE_URL' => $this->root . $this->urlTemplates['detail'],
            'LIST_PAGE_URL' => $this->root . $this->urlTemplates['list'],
            'HISTORY_PAGE_URL' => $this->root . $this->urlTemplates['history'],
        ];

        $this->includeComponentTemplate($page ?: $this->defaultPage);
    }
}