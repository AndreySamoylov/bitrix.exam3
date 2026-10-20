<?php

namespace Academy\InvestmentProject;

use Academy\InvestmentProject\History\Entry as HistoryItem;
use Academy\InvestmentProject\History\Service as HistoryService;
use Academy\InvestmentProject\History\ServiceException as HistoryServiceException;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Main\ORM\Fields\FieldTypeMask;
use Bitrix\Main\ORM\Query\Filter\ConditionTree;
use Bitrix\Main\SystemException;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\UI\PageNavigation;
use Exception;

class Service
{
    public const TRACKED_FIELDS = [
        'RESPONSIBLE_ID',
        'TITLE',
        'COMMENT',
        'COMPLETION_DATE',
        'ESTIMATED_COMPLETION_DATE',
        'DESCRIPTION',
        'INCOME',
    ];

    /**
     * Не использует транзакции, но должен.
     * Применение и управление транзакциями выходят за пределы темы урока.
     */
    public function __construct(
        private readonly HistoryService $historyService,
        private readonly CurrentUser $currentUser
    ) {
    }

    /**
     * Подсчитывает общее количество Инвест Проектов по заданному фильтру.
     * Используется для корректной работы {@link PageNavigation}.
     *
     * @throws ServiceException
     */
    public function count(Filter $filter): int
    {
        try {
            return InvestmentProjectTable::query()->where($filter->toCriteria())->queryCountTotal();
        } catch (SystemException $e) {
            throw new ServiceException('Failed to count projects', previous: $e);
        }
    }

    /**
     * Получает фрагмент списка Инвест Проектов.
     *
     * Фрагмент по определению не может содержать полный список Инвестиционных проектов, поэтому параметры
     * $size и $pageNumber - обязательные.
     *
     * @throws ServiceException
     */
    public function getFragment(Filter $filter, array $order, int $size, int $pageNumber): Collection
    {
        if ($size < 0) {
            throw new ServiceException('Page size MUST be a positive integer.');
        }

        $offset = (max($pageNumber, 1) - 1) * $size;

        return $this->findProjects($filter->toCriteria(), $order, $size, $offset);
    }

    /**
     * Общая часть для всех публичных методов, которым необходимо получение Инвест Проектов из БД.
     *
     * @throws ServiceException
     */
    private function findProjects(
        ConditionTree $criteria,
        array $order,
        ?int $limit = null,
        ?int $offset = null
    ): Collection {
        try {
            $result = InvestmentProjectTable::query()
                ->setSelect([
                    'ID',
                    'TITLE',
                    'CREATED_AT',
                    'CREATED_BY',
                    'UPDATED_AT',
                    'UPDATED_BY',
                    'ESTIMATED_COMPLETION_DATE',
                    'COMPLETION_DATE',
                    'DESCRIPTION',
                    'RESPONSIBLE_ID',
                    'COMMENT',
                    'INCOME'
                ])
                ->where($criteria)
                ->setOrder($order)
                ->setLimit($limit)
                ->setOffset($offset)
                ->exec();

            $projects = new Collection();
            foreach ($result->fetchCollection() as $entityObject) {
                $projects->insert(
                    new Project(
                        $entityObject->getId(),
                        $entityObject->getTitle(),
                        $entityObject->getCreatedAt(),
                        $entityObject->getCreatedBy(),
                        $entityObject->getUpdatedAt(),
                        $entityObject->getUpdatedBy(),
                        $entityObject->getEstimatedCompletionDate() ?: null,
                        $entityObject->getCompletionDate() ?: null,
                        $entityObject->getDescription(),
                        $entityObject->getResponsibleId(),
                        $entityObject->getComment(),
                        $entityObject->getIncome(),
                    )
                );
            }

            return $projects;
        } catch (SystemException $e) {
            throw new ServiceException('Failed to find project', previous: $e);
        }
    }

    /**
     * Создает Инвест Проект.
     *
     * Всегда задает значения для системных полей (кем создано, когда создано, кем изменено, когда изменено).
     *
     * Все значения изменяемых пользователем полей записываются в Историю изменений проекта.
     *
     * @throws ServiceException
     */
    public function create(Project $project): Project
    {
        $currentTime = new DateTime();
        $currentUserId = $this->currentUser->getId();

        $project->createdBy = $currentUserId;
        $project->updatedBy = $currentUserId;
        $project->createdAt = $currentTime;
        $project->updatedAt = $currentTime;

        try {
            $entityObject = InvestmentProjectTable::createObject()
                ->setTitle($project->title)
                ->setCreatedAt($project->createdAt)
                ->setCreatedBy($project->createdBy)
                ->setUpdatedAt($project->updatedAt)
                ->setUpdatedBy($project->updatedBy)
                ->setEstimatedCompletionDate($project->estimatedCompletionDate ?? '')
                ->setCompletionDate($project->completionDate ?? '')
                ->setDescription($project->description)
                ->setResponsibleId($project->responsibleId)
                ->setComment($project->comment)
                ->setIncome($project->income);

            $addResult = $entityObject->save();
        } catch (Exception $e) {
            throw new ServiceException('Failed to add project', previous: $e);
        }

        if (!$addResult->isSuccess()) {
            throw ServiceException::createFromCollection($addResult->getErrorCollection());
        }

        $project = $project->withId($addResult->getId());

        $history = array_filter(
            $entityObject->collectValues(fieldsMask: FieldTypeMask::FLAT),
            static fn(string $fieldName): bool => in_array($fieldName, Service::TRACKED_FIELDS, true),
            ARRAY_FILTER_USE_KEY
        );

        try {
            foreach ($history as $field => $value) {
                $this->historyService->register(
                    new HistoryItem(
                        null,
                        $project->id,
                        $project->responsibleId,
                        $field,
                        null,
                        $value,
                        $project->createdAt
                    )
                );
            }
        } catch (HistoryServiceException $e) {
            throw new ServiceException('Failed to add project', previous: $e);
        }

        return $project;
    }

    /**
     * Обновляет Инвест Проект.
     *
     * Всегда задает значения для полей "кем изменено" и "когда изменено".
     *
     * Записывает в Историю изменений те поля, которые были изменены в сравнении с предыдущим состоянием в БД.
     * Значения полей сравниваются нестрого.
     *
     * Изменения значений системных полей в историю не попадают.
     *
     * @throws NotFoundException
     * @throws ServiceException
     */
    public function update(Project $project): void
    {
        $project->updatedAt = new DateTime();
        $project->updatedBy = $this->currentUser->getId();

        try {
            $actualProject = InvestmentProjectTable::getById($project->id)->fetchObject();
        } catch (SystemException $e) {
            throw new NotFoundException('Project not found', previous: $e);
        }

        $history = [];

        try {
            $entityObject = $actualProject
                ->setTitle($project->title)
                ->setCreatedAt($project->createdAt)
                ->setCreatedBy($project->createdBy)
                ->setUpdatedAt($project->updatedAt)
                ->setUpdatedBy($project->updatedBy)
                ->setEstimatedCompletionDate($project->estimatedCompletionDate ?? '')
                ->setCompletionDate($project->completionDate ?? '')
                ->setDescription($project->description)
                ->setResponsibleId($project->responsibleId)
                ->setComment($project->comment)
                ->setIncome($project->income);

            foreach ($entityObject->collectValues(fieldsMask: FieldTypeMask::FLAT) as $field => $currentValue) {
                if (!in_array($field, Service::TRACKED_FIELDS, true)) {
                    continue;
                }

                if (!$entityObject->isChanged($field)) {
                    continue;
                }

                $history[] = new HistoryItem(
                    null,
                    $project->id,
                    $project->updatedBy,
                    $field,
                    $entityObject->remindActual($field),
                    $currentValue,
                    $project->updatedAt
                );
            }

            $updateResult = $entityObject->save();
        } catch (Exception $e) {
            throw new ServiceException('Failed to update project', previous: $e);
        }

        if (!$updateResult->isSuccess()) {
            throw ServiceException::createFromCollection($updateResult->getErrorCollection());
        }

        try {
            foreach ($history as $entry) {
                $this->historyService->register($entry);
            }
        } catch (HistoryServiceException $e) {
            throw new ServiceException('Failed to update project', previous: $e);
        }
    }

    /**
     * Получает конкретный Инвест Проект по идентификатору из БД.
     *
     * @throws NotFoundException
     * @throws ServiceException
     */
    public function getById(int $projectId): Project
    {
        $criteria = new ConditionTree();
        try {
            $criteria->where('ID', '=', $projectId);
        } catch (ArgumentException) {
            // Noop, never thrown.
        }

        $projects = $this->findProjects($criteria, ['ID' => 'DESC']);

        if ($projects->isEmpty()) {
            throw new NotFoundException('Project not found.');
        }

        return $projects->get($projectId);
    }


    /**
     * @throws ServiceException
     */
    public function getByIds(int ...$projectIds): Collection
    {
        if (empty($projectIds)) {
            return new Collection();
        }

        $criteria = new ConditionTree();
        $criteria->whereIn('ID', $projectIds);

        return $this->findProjects($criteria, ['ID' => 'DESC']);
    }

    /**
     * @throws ServiceException
     */
    public function delete(Project $project): void
    {
        try {
            $deleteResult = InvestmentProjectTable::getById($project->id)->fetchObject()->delete();

            $this->historyService->clearByProject($project);
        } catch (Exception $e) {
            throw new ServiceException('Failed to delete project', previous: $e);
        }

        if (!$deleteResult->isSuccess()) {
            throw ServiceException::createFromCollection($deleteResult->getErrorCollection());
        }
    }
}