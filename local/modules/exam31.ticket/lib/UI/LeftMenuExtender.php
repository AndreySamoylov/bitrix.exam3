<?php

namespace Exam31\Ticket\UI;

use Bitrix\Main\Context;
use Bitrix\Main\Page\Asset;
use Bitrix\Main\Localization\Loc;

class LeftMenuExtender
{
    public static function addLeftMenuItem(): void
    {
        global $USER;
        if (
            Context::getCurrent()->getRequest()->isAdminSection()
            || !$USER->IsAdmin()
        ) {
            return;
        }

        $message = Loc::getMessage('EXAM31_UI_ADMIN_BUTTON');

        Asset::getInstance()->addString(
            <<<HTML
            <script>
            BX.ready(function() {
                const menuItemServicesSect = document.querySelector('#bx_left_menu_menu_services_sect');
                
                if (menuItemServicesSect === null) {
                    console.warn('Menu items footer is missing.');
                    return;
                }
                
                const menuItem = document.createElement('li');
                menuItem.id = 'adminButtonItem';
                menuItem.className = 'menu-item-block menu-services-sect';
                
                const anchor = document.createElement('a');
                anchor.href = '/bitrix/admin/index.php';
                anchor.text = '$message';
                anchor.className = 'menu-item-link'
                anchor.target = '_blank'
                menuItem.append(anchor);
                
                menuItemServicesSect.parentNode.append(menuItem)
            })
            </script>

            HTML
        );
    }
}