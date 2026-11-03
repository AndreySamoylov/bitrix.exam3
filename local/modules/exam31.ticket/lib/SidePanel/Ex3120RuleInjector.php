<?php

namespace Exam31\Ticket\SidePanel;

use Bitrix\Main\Page\Asset;
use Bitrix\Main\UI\Extension;

class Ex3120RuleInjector
{
    public static function injectAnchorsRules()
    {
        Extension::load('sidepanel');

        $asset = Asset::getInstance();

        $asset->addString(
            $asset->insertJs(
                <<<JS
                BX.ready(() => {
                if (window.top !== window) {
                    return;
                }
                
                BX.SidePanel.Instance.bindAnchors(
                    {
                        rules: [
                            {
                                condition: [new RegExp('/exam31-train/info/[0-9]+/')],
                                options: {
                                    width: 1500,
                                    cacheable: false,
                                }      
                            }
                        ]
                    }
                )
                })
                JS,
                inline: true
            )
        );
    }
}
