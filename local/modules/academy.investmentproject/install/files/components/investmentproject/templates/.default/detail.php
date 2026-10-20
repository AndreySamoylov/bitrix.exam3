<?php


use Bitrix\Main\Application;

defined('B_PROLOG_INCLUDED') || die;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CMain $APPLICATION
 */

$request = Application::getInstance()->getContext()->getRequest();

$APPLICATION->IncludeComponent(
    'bitrix:ui.sidepanel.wrapper',
    '.default',
    [
        'POPUP_COMPONENT_NAME' => 'academy.investmentproject:investmentproject.detail',
        'POPUP_COMPONENT_TEMPLATE_NAME' => '.default',
        'POPUP_COMPONENT_PARAMS' => [
            'INVESTMENT_PROJECT_ID' => $arResult['VARIABLES']['INVESTMENT_PROJECT_ID'] ?? null,
            'DETAIL_PAGE_URL' => $arResult['DETAIL_PAGE_URL'],
            'HISTORY_PAGE_URL' => $arResult['HISTORY_PAGE_URL'],
            'LIST_PAGE_URL' => $arResult['LIST_PAGE_URL'],
        ],
        'USE_UI_TOOLBAR' => 'Y',
        'RELOAD_GRID_AFTER_SAVE' => true,
        'PAGE_MODE' => false,
        'PAGE_MODE_OFF_BACK_URL' => $arResult['LIST_PAGE_URL']
    ]
);
