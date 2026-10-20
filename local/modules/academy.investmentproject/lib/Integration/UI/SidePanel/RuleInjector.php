<?php

namespace Academy\InvestmentProject\Integration\UI\SidePanel;

use Bitrix\Main\Page\Asset;

final class RuleInjector
{
    /**
     * Обработчик события "main:OnEpilog".
     *
     * Внедряет правила разрешения переходов по ссылкам для детальной сущности Инвест Проектов и Истории изменений Проекта.
     * Битрикс требует, чтобы внедрение происходило только на основной странице.
     *
     * @link https://dev.1c-bitrix.ru/api_help/js_lib/sidepanel/sidepanel_instance.php
     *
     * @return void
     */
    public static function injectAnchorRules(): void
    {
        $asset = Asset::getInstance();

        $asset->addString(
            $asset->insertJs(
                <<<JS
                BX.ready(() => {
                    if (window.top !== window) {
                        return;
                    }
                    
                    BX.SidePanel.Instance.bindAnchors({
                        rules: [
                            {
                                condition: [new RegExp('/invest/detail/[0-9]+/')],
                                options: {
                                    width: 900,
                                    cacheable: false,
                                    label: {
                                        text: 'Проект'
                                    }
                                }
                            },
                            {
                                condition: [new RegExp('/invest/history/[0-9]+/')],
                                options: {
                                    width: 900,
                                    cacheable: false
                                }
                            }
                        ]
                    });
                });
                JS,
                inline: true
            )
        );
    }
}