<?php


require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';

/**
 * @var CMain $APPLICATION
 */

$APPLICATION->IncludeComponent(
    'academy.investmentproject:investmentproject',
    '.default',
    [
        'SEF_FOLDER' => '/invest/',
        'URL_TEMPLATES' => [
            'list' => 'list/',
            'detail' => 'detail/#INVESTMENT_PROJECT_ID#/',
            'history' => 'history/#INVESTMENT_PROJECT_ID#/',
        ],
        'DEFAULT_PAGE' => 'list'
    ]
);

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';