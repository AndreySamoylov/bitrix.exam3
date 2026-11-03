<?php
namespace Exam31\Ticket;

use Bitrix\Main\Entity;

class Ex3120InfoTable extends Entity\DataManager
{
	static function getTableName(): string
	{
		return 'exam3_120_info_element';
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
		);
	}
}
