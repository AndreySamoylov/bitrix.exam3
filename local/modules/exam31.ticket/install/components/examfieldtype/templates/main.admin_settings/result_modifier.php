<?php

defined('B_PROLOG_INCLUDED') || die;

$values = [];
if (
	isset($arResult['additionalParameters']['bVarsFromForm'])
	&& $arResult['additionalParameters']['bVarsFromForm'])
{
	$values['FORMAT'] = $GLOBALS[$arResult['additionalParameters']['NAME']]['FORMAT'] ?? '';
    $values['DETAIL_PAGE_FORMAT'] = $GLOBALS[$arResult['additionalParameters']['NAME']]['DETAIL_PAGE_FORMAT'] ?? '';
}
elseif (isset($arResult['userField']) && $arResult['userField'])
{
	$values['FORMAT'] = $arResult['userField']['SETTINGS']['FORMAT'];
    $values['DETAIL_PAGE_FORMAT'] = $arResult['userField']['SETTINGS']['DETAIL_PAGE_FORMAT'];
}

$arResult['VALUES'] = $values;