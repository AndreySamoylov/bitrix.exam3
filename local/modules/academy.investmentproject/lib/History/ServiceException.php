<?php

namespace Academy\InvestmentProject\History;

use Bitrix\Main\Error;
use Bitrix\Main\ErrorCollection;
use Exception;

final class ServiceException extends Exception
{
    public static function createFromCollection(ErrorCollection $collection): ServiceException
    {
        $messages = array_map(
            static fn(Error $error): string => $error->getMessage(),
            $collection->toArray()
        );

        return new ServiceException(implode("\n", $messages));
    }
}