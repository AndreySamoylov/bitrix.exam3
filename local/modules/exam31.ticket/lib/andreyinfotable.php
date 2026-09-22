<?php
namespace Exam31\Ticket;

use Bitrix\Main\Entity;

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
            (new Entity\IntegerField('ELEMENT_ID'))
		);
	}
}
