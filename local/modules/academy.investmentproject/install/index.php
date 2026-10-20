<?php

use Academy\InvestmentProject\History\HistoryTable;
use Academy\InvestmentProject\Integration\Rest\Service;
use Academy\InvestmentProject\Integration\UI\SidePanel\RuleInjector;
use Academy\InvestmentProject\InvestmentProjectTable;
use Bitrix\Main\Application;
use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentTypeException;
use Bitrix\Main\Config\Option;
use Bitrix\Main\EventManager;
use Bitrix\Main\IO\File;
use Bitrix\Main\IO\Path;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\ORM\Entity;
use Bitrix\Main\UrlRewriter;

defined('B_PROLOG_INCLUDED') || die;

final class academy_investmentproject extends CModule
{
    public function __construct()
    {
        $this->MODULE_ID = 'academy.investmentproject';
        $this->MODULE_NAME = Loc::getMessage('ACADEMY.INVESTMENTPROJECT_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('ACADEMY.INVESTMENTPROJECT_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('ACADEMY.INVESTMENTPROJECT_PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('ACADEMY.INVESTMENTPROJECT_PARTNER_URI');

        /** @var array{MODULE_VERSION: string, MODULE_VERSION_DATE: string} $version */
        $version = include __DIR__ . '/version.php';

        $this->MODULE_VERSION = $version['MODULE_VERSION'];
        $this->MODULE_VERSION_DATE = $version['MODULE_VERSION_DATE'];
    }

    /**
     * @throws LoaderException
     */
    public function DoInstall(): void
    {
        $this->InstallDB();
        $this->InstallFiles();
    }

    /**
     * @throws LoaderException
     */
    public function InstallDB(): void
    {
        ModuleManager::registerModule($this->MODULE_ID);
        Loader::requireModule($this->MODULE_ID);

        $this->installEntities();
        $this->addLeftMenuItem();
        $this->registerEventHandlers();
    }

    private function installEntities(): void
    {
        Application::getConnection()->executeSqlBatch(
            File::getFileContents(Path::combine(__DIR__, 'mysql/install.sql'))
        );
    }

    private function addLeftMenuItem(): void
    {
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

        $leftMenu[] = [
            'TEXT' => Loc::getMessage('ACADEMY.INVESTMENTPROJECT_MENU_ITEM_TEXT'),
            'LINK' => '/invest/',
            'ID' => 'investment-project',
        ];
        Option::set('intranet', 'left_menu_items_to_all_s1', serialize($leftMenu));
    }

    private function registerEventHandlers(): void
    {
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
    }

    public function InstallFiles(): void
    {
        try {
            CopyDirFiles(
                Path::combine(__DIR__, '/files/components/'),
                Path::convertRelativeToAbsolute(Path::combine('local/components/academy.investmentproject/')),
                true,
                true
            );
        } catch (ArgumentNullException|ArgumentTypeException) {
            // Noop, never happens.
        }

        CopyDirFiles(
            Path::combine(__DIR__, '/files/public/'),
            Application::getDocumentRoot(),
            true,
            true
        );

        try {
            UrlRewriter::add('s1', [
                'ID' => 'academy.investmentproject:investmentproject',
                'CONDITION' => '#^/invest/#',
                'PATH' => '/invest/index.php'
            ]);
        } catch (ArgumentNullException) {
            // Noop, never happens because $siteId is a string literal.
        }
    }

    /**
     * @throws LoaderException
     */
    public function DoUninstall(): void
    {
        $this->UnInstallFiles();
        $this->UnInstallDB();
    }

    public function UnInstallFiles(): void
    {
        DeleteDirFilesEx('local/components/academy.investmentproject');
        DeleteDirFilesEx('invest');

        try {
            UrlRewriter::delete('s1', ['ID' => 'academy.investmentproject:investmentproject']);
        } catch (ArgumentNullException) {
            // Noop, never happens because $siteId is a string literal.
        }
    }

    /**
     * @throws LoaderException
     */
    public function UnInstallDB(): void
    {
        Loader::requireModule($this->MODULE_ID);

        $this->removeLeftMenuItem();
        $this->unregisterEventHandlers();
        $this->uninstallEntities();

        ModuleManager::unRegisterModule($this->MODULE_ID);
        Loader::clearModuleCache($this->MODULE_ID);
    }

    private function removeLeftMenuItem(): void
    {
        $leftMenu = Option::get('intranet', 'left_menu_items_to_all_s1');

        if (empty($leftMenu)) {
            return;
        }

        $leftMenu = unserialize($leftMenu, ['allowed_classes' => false]);
        foreach ($leftMenu as $index => $item) {
            if ($item['ID'] === 'investment-project') {
                unset($leftMenu[$index]);
                break;
            }
        }

        Option::set('intranet', 'left_menu_items_to_all_s1', serialize($leftMenu));
    }

    private function unregisterEventHandlers(): void
    {
        $eventManager = EventManager::getInstance();
        $eventManager->unRegisterEventHandler(
            'main',
            'OnEpilog',
            $this->MODULE_ID,
            RuleInjector::class,
            'injectAnchorRules'
        );

        $eventManager->unRegisterEventHandler(
            'rest',
            'OnRestServiceBuildDescription',
            $this->MODULE_ID,
            Service::class,
            'onRestServiceBuildDescription'
        );
    }

    /**
     * Так делать не совсем правильно, потому что пользователям модуля могут понадобиться таблицы даже после удаления модуля.
     *
     * На самом деле нужно реализовать отдельный шаг процесса удаления модуля, в котором спросить пользователя "удалить данные модуля?".
     * Эта тема выходит за рамки урока и поэтому не рассматривается.
     */
    private function uninstallEntities(): void
    {
        Application::getConnection()->executeSqlBatch(
            File::getFileContents(Path::combine(__DIR__, 'mysql/uninstall.sql'))
        );
    }
}