<?php

defined('B_PROLOG_INCLUDED') || die;

use Academy\InvestmentProject\Integration\Rest\Service;
use Academy\InvestmentProject\Integration\UI\SidePanel\RuleInjector;
use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\EventManager;
use Bitrix\Main\IO\File;
use Bitrix\Main\IO\Path;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UrlRewriter;

Loader::requireModule('academy.investmentproject');

//region Установка таблиц.
$connection = Application::getConnection()
    ->executeSqlBatch(
        File::getFileContents(Path::combine(__DIR__, '..', 'mysql/install.sql'))
    );
//endregion

//region Установка файлов, регистрация правила urlrewrite.
CopyDirFiles(
    Path::combine(__DIR__, '..', '/files/components/'),
    Path::convertRelativeToAbsolute(Path::combine('local/components/academy.investmentproject/')),
    true,
    true
);

CopyDirFiles(
    Path::combine(__DIR__, '..', '/files/public/'),
    Application::getDocumentRoot(),
    true,
    true
);

UrlRewriter::add('s1', [
    'ID' => 'academy.investmentproject:investmentproject',
    'CONDITION' => '#^/invest/#',
    'PATH' => '/invest/index.php'
]);
//endregion

//region Добавление пункта в левое меню.
$leftMenu = Option::get('intranet', 'left_menu_items_to_all_s1');

if (!empty($leftMenu)) {
    $leftMenu = unserialize($leftMenu, ['allowed_classes' => false]);

    foreach ($leftMenu as $item) {
        if ($item['ID'] === 'investment-project') {
            return;
        }
    }
} else {
    $leftMenu = [];
}

Loc::loadMessages(Path::combine(__DIR__, '..', 'index.php'));
$leftMenu[] = [
    'TEXT' => Loc::getMessage('ACADEMY.INVESTMENTPROJECT_MENU_ITEM_TEXT'),
    'LINK' => '/invest/',
    'ID' => 'investment-project',
];
Option::set('intranet', 'left_menu_items_to_all_s1', serialize($leftMenu));
//endregion

//region Регистрация обработчиков событий.
$eventManager = EventManager::getInstance();
$eventManager->registerEventHandler(
    'main',
    'OnEpilog',
    $this->MODULE_ID,
    RuleInjector::class,
    'injectAnchorRules'
);

$eventManager->registerEventHandler(
    'rest',
    'OnRestServiceBuildDescription',
    $this->MODULE_ID,
    Service::class,
    'onRestServiceBuildDescription'
);
//endregion

Option::set('academy.investmentproject', 'VERSION', 2024_02_01_11_24_00);
