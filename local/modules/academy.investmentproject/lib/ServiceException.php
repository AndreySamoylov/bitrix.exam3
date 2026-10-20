<?php

namespace Academy\InvestmentProject;

use Bitrix\Main\Error;
use Bitrix\Main\ErrorCollection;
use Exception;

class ServiceException extends Exception
{
    final public static function createFromCollection(ErrorCollection $collection): ServiceException
    {
        $messages = array_map(static fn(Error $e): string => $e->getMessage(), $collection->toArray());
        return new ServiceException(implode("\n", $messages));
    }
}