<?php
namespace Exam31\Ticket;

use Bitrix\Main\Entity;
use Bitrix\Main\Type\DateTime;

class AndreyElementTable extends Entity\DataManager
{
	static function getTableName(): string
	{
		return 'exam31_ticket_andrey_element';
	}
	static function getMap(): array
	{
		return array(
			(new Entity\IntegerField('ID'))
				->configurePrimary()
				->configureAutocomplete(),
            (new Entity\DatetimeField('DATE_MODIFY'))
                ->configureRequired()
                ->configureDefaultValue(new DateTime()),
			(new Entity\BooleanField('ACTIVE'))
				->configureRequired()
                ->configureValues('N', 'Y'),
			(new Entity\StringField('TITLE'))
				->configureRequired()
                ->configureSize(250),
			new Entity\TextField('TEXT'),
		);
	}
}
