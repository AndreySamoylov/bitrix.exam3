<?php

namespace Exam31\Ticket;

use Bitrix\Main\Entity;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;

class AndreyInfoTable extends Entity\DataManager
{
    static function getTableName(): string
    {
        return 'exam31_ticket_andrey_info';
    }

    static function getMap(): array
    {
        return array(
            (new Entity\IntegerField('ID'))
                ->configurePrimary()
                ->configureAutocomplete(),
            (new Entity\StringField('TITLE'))
                ->configureRequired()
                ->configureSize(250),
            (new Entity\IntegerField('ELEMENT_ID')),
            (new Reference('ELEMENT',
                AndreyElementTable::class,
                Join::on('this.ELEMENT_ID', 'ref.ID')
                ))
                ->configureJoinType('inner')
        );
    }
}
