<?php

use Bitrix\UI\Toolbar\Facade\Toolbar;

B_PROLOG_INCLUDED === true || die();

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */



Toolbar::addFilter(
    $arResult['filter']
);

foreach ($arResult['toolbar']['buttons'] as $button) {
    Toolbar::addButton($button);
}


$APPLICATION->IncludeComponent(
	'bitrix:main.ui.grid',
	'',
	$arResult["grid"],
	$component
);
