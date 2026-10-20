<?php


use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UI\Extension;
use Bitrix\Main\Web\Json;
use Bitrix\UI\Toolbar\Facade\Toolbar;

defined('B_PROLOG_INCLUDED') || die;


/**
 * @var CMain $APPLICATION
 * @var array $arResult
 */
Extension::load('ui.dialogs.messagebox');
Extension::load('ui.notification');
$APPLICATION->SetTitle(Loc::getMessage('INVESTMENT_PROJECT_LIST_PAGE_TITLE'));

foreach ($arResult['toolbar']['buttons'] as $button) {
    Toolbar::addButton($button);
}

Toolbar::addFilter($arResult['filter']);
$APPLICATION->IncludeComponent(
    'bitrix:main.ui.grid',
    '.default',
    $arResult['grid']
);


$jsonData = Json::encode([
    'messages' => Loc::loadLanguageFile(__FILE__),
    'gridManager' => $arResult['gridManager']
]);
?>
<script>
    BX.ready(() => {
        const data = <?= $jsonData; ?>;

        BX.message(data.messages);
        BX.Academy.InvestmentProject.Grid.Manager.initialize(data.gridManager);
    })
</script>
