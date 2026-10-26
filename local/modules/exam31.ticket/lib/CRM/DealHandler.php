<?php

namespace Exam31\Ticket\CRM;

use Bitrix\Main\Loader;
use Bitrix\Crm\Service\Container;
use Bitrix\Main\Localization\Loc;

class DealHandler
{
    public static function OnBeforeCrmDealUpdateHandler(array &$fields): bool
    {
        Loader::includeModule('crm');

        global $USER;
        if ($USER->IsAdmin()) {
            return true;
        }

        $dealId = $fields['ID'];
        $dealFactory = Container::getInstance()->getFactory(\CCrmOwnerType::Deal);
        $deal = $dealFactory->getItem($dealId);

        $protectedField = (string) $deal->get('UF_CRM_PROTECTED_FIELD');

        $isProtectedFieldUpdate = $protectedField !== $fields['UF_CRM_PROTECTED_FIELD'];

        if ($isProtectedFieldUpdate) {
            $fields['RESULT_MESSAGE'] = Loc::getMessage('ERROR_MESSAGE');
            return false;
        }

        return true;
    }
}
