<?php

defined('B_PROLOG_INCLUDED') || die;

$values = [];
if (
	isset($arResult['additionalParameters']['bVarsFromForm'])
	&& $arResult['additionalParameters']['bVarsFromForm'])
{
	$values['FORMAT'] = $GLOBALS[$arResult['additionalParameters']['NAME']]['FORMAT'] ?? '';
	$values['INFO_DETAIL_PAGE'] = $GLOBALS[$arResult['additionalParameters']['NAME']]['INFO_DETAIL_PAGE'] ?? '';
}
elseif (isset($arResult['userField']) && $arResult['userField'])
{
	$values['FORMAT'] = $arResult['userField']['SETTINGS']['FORMAT'];
	$values['INFO_DETAIL_PAGE'] = $arResult['userField']['SETTINGS']['INFO_DETAIL_PAGE'];
}

$arResult['VALUES'] = $values;