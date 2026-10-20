### Создание ORM аннотаций
- Установить `composer`, инструкция: https://getcomposer.org/download/;
- Выполнить `COMPOSER=composer-bx.json composer install`, находясь в директории `<docroot>/bitrix/`;
- Дать право выполнения файлу `<docroot>/bitrix/bitrix.php`: `chmod +x bitrix.php`;
- Выполнить `./bitrix.php orm:annotate -m <module.name> <moduleRoot>/meta/orm.php`;

### Создание демонстрационных данных

```php
use Bitrix\Main\Loader;

Loader::requireModule('academy.investmentproject');

for ($i = 0; $i < 25; $i++) {
    $entityObject = \academy\investmentproject\InvestmentProjectTable::createObject()
        ->setTitle("Проект $i")
        ->setCreatedAt((new \Bitrix\Main\Type\DateTime()))
        ->setCreatedBy($USER->GetID())
        ->setUpdatedAt((new \Bitrix\Main\Type\DateTime()))
        ->setUpdatedBy($USER->GetID())
        ->setEstimatedCompletionDate('')
        ->setCompletionDate('')
        ->setDescription("Описание $i")
        ->setResponsibleId($USER->GetID())
        ->setComment("Комментарий $i")
        ->setIncome('');

    $entityObject->save();
}

```
