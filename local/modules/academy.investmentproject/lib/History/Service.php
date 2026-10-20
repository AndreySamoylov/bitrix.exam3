<?php

namespace Academy\InvestmentProject\History;

use Academy\InvestmentProject\Project;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\ORM\Query\Filter\ConditionTree;
use Bitrix\Main\ORM\Query\Query;
use Bitrix\Main\SystemException;
use Exception;

class Service
{
    /**
     * @throws ServiceException
     */
    public function count(Filter $filter): int
    {
        try {
            return $this->createQuery()->where($filter->toCriteria())->queryCountTotal();
        } catch (SystemException $e) {
            throw new ServiceException('Failed to count history entries', previous: $e);
        }
    }

    /**
     * @return EO_History_Query
     *
     * @throws SystemException
     */
    protected function createQuery(): Query
    {
        $query = HistoryTable::query();
        $query->setSelect([
            'ID',
            'PROJECT_ID',
            'AUTHOR_ID',
            'FIELD_NAME',
            'PREVIOUS_VALUE',
            'CURRENT_VALUE',
            'CHANGED_AT',
        ]);

        return $query;
    }

    /**
     * Получает фрагмент списка истории изменений проектов.
     *
     * Фильтрация по идентификатору проекта возлагается на вызывающего.
     *
     * @throws ServiceException
     */
    public function getFragment(Filter $filter, array $order, int $pageSize, int $pageNumber): Collection
    {
        $offset = (max($pageNumber, 1) - 1) * $pageSize;

        try {
            $result = $this
                ->createQuery()
                ->where($filter->toCriteria())
                ->setLimit($pageSize)
                ->setOffset($offset)
                ->setOrder($order)
                ->exec();

            $collection = new Collection();
            foreach ($result->fetchCollection() as $entityObject) {
                $collection->insert(
                    new Entry(
                        $entityObject->getId(),
                        $entityObject->getProjectId(),
                        $entityObject->getAuthorId(),
                        $entityObject->getFieldName(),
                        $entityObject->getPreviousValue(),
                        $entityObject->getCurrentValue(),
                        $entityObject->getChangedAt(),
                    )
                );
            }

            return $collection;
        } catch (SystemException $e) {
            throw new ServiceException('Failed to find history entries', previous: $e);
        }
    }

    /**
     * Создает запись в истории изменений.
     *
     * @throws ServiceException
     */
    public function register(Entry $historyItem): Entry
    {
        try {
            $entityObject = HistoryTable::createObject()
                ->setProjectId($historyItem->projectId)
                ->setAuthorId($historyItem->authorId)
                ->setFieldName($historyItem->fieldName)
                ->setPreviousValue($historyItem->previousValue ?? '')
                ->setCurrentValue($historyItem->currentValue ?? '')
                ->setChangedAt($historyItem->changedAt);

            $addResult = $entityObject->save();
        } catch (Exception $e) {
            throw new ServiceException('Failed to add history entry', previous: $e);
        }

        if (!$addResult->isSuccess()) {
            throw ServiceException::createFromCollection($addResult->getErrorCollection());
        }

        return $historyItem->withId($addResult->getId());
    }

    /**
     * Удаляет все записи из истории изменений по конкретному проекту.
     *
     * @throws ServiceException
     */
    public function clearByProject(Project $project): void
    {
        $criteria = new ConditionTree();
        try {
            $criteria->where('PROJECT_ID', '=', $project->id);
        } catch (ArgumentException) {
            // noop, never happens because operator is a string literal.
        }

        try {
            $result = $this->createQuery()->where($criteria)->exec();

            $collection = $result->fetchCollection();
        } catch (SystemException $e) {
            throw new ServiceException('Failed to find history entries', previous: $e);
        }

        foreach ($collection as $item) {
            try {
                $deleteResult = $item->delete();
            } catch (Exception $e) {
                throw new ServiceException('Failed to delete history entry', previous: $e);
            }

            if (!$deleteResult->isSuccess()) {
                throw ServiceException::createFromCollection($deleteResult->getErrorCollection());
            }
        }
    }
}