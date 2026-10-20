<?php


defined('B_PROLOG_INCLUDED') || die;


/**
 * @var array $arResult
 * @var CMain $APPLICATION
 * @var array $arParams
 */
$APPLICATION->IncludeComponent(
    'academy.investmentproject:investmentproject.list',
    '.default', [
        'INVESTMENT_PROJECT_ID' => $arResult['VARIABLES']['INVESTMENT_PROJECT_ID'] ?? null,
        'DETAIL_PAGE_URL' => $arResult['DETAIL_PAGE_URL'],
        'HISTORY_PAGE_URL' => $arResult['HISTORY_PAGE_URL'],
        'LIST_PAGE_URL' => $arResult['LIST_PAGE_URL'],
    ]
);