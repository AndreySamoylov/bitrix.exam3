<?php


use Bitrix\Main\UI\Extension;
use Bitrix\UI\Toolbar\Facade\Toolbar;

defined('B_PROLOG_INCLUDED') || die;


/**
 * @var CMain $APPLICATION
 * @var array $arResult
 * @var array $arParams
 */

Extension::load('ui.entity-selector');

foreach ($arResult['toolbar']['buttons'] as $button) {
    Toolbar::addButton($button);
}

$APPLICATION->SetTitle($arResult['title']);
$APPLICATION->IncludeComponent(
    'bitrix:ui.form',
    '.default',
    $arResult['form']
);
?>
<script>
    BX.ready(() => {
        const manager = new BX.Academy.InvestmentProject.Detail.Manager();
        manager.bindEvents();
    })
</script>
