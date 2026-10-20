<?php


use Bitrix\Main\Localization\Loc;
use Bitrix\UI\Toolbar\Facade\Toolbar;

defined('B_PROLOG_INCLUDED') || die;


/**
 * @var CMain $APPLICATION
 * @var array $arResult
 */
$APPLICATION->SetTitle(Loc::getMessage('INVESTMENT_PROJECT_HISTORY_PAGE_TITLE'));

foreach ($arResult['toolbar']['buttons'] as $button) {
    Toolbar::addButton($button);
}

Toolbar::addFilter($arResult['filter']);
$APPLICATION->IncludeComponent(
    'bitrix:main.ui.grid',
    '.default',
    $arResult['grid']
);
