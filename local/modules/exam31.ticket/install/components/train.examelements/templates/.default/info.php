<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

/**
 * @var array $arResult
 * @var CMain $APPLICATION
 * @var array $arParams
 */

$APPLICATION->IncludeComponent(
    'bitrix:ui.sidepanel.wrapper',
    '',
    [
        'POPUP_COMPONENT_NAME' => 'exam31.ticket:train.examelements.detail',
        'POPUP_COMPONENT_TEMPLATE_NAME' => 'form',
        'POPUP_COMPONENT_PARAMS' => [
            'ELEMENT_ID' => $arResult['VARIABLES']['ELEMENT_ID'] ?? null,
            'DETAIL_PAGE_URL' => $arResult['DETAIL_PAGE_URL'],
            'LIST_PAGE_URL' => $arResult['LIST_PAGE_URL'],
            'IS_INFO' => 'Y',
        ],
        'PAGE_MODE' => false,
        'PAGE_MODE_OFF_BACK_URL' => $arResult['LIST_PAGE_URL'],
    ]
);
