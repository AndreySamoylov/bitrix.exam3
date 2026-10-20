<?php

namespace Academy\InvestmentProject\Integration\Rest;

use Academy\InvestmentProject\Filter;
use Academy\InvestmentProject\Filter\Type\DateRange;
use Academy\InvestmentProject\History\Service as HistoryService;
use Academy\InvestmentProject\NotFoundException;
use Academy\InvestmentProject\Project;
use Academy\InvestmentProject\Service as ProjectService;
use Academy\InvestmentProject\ServiceException;
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Rest\RestException;
use CRestServer;
use CRestUtil;
use IRestService;

final class Service extends IRestService
{
    private const SCOPE = 'academy.investmentproject';

    public function __construct(private readonly ProjectService $projectService)
    {
    }

    /**
     * Обработчик события `rest:OnRestServiceBuildDescription`.
     *
     * @return array[]
     */
    public static function onRestServiceBuildDescription(): array
    {
        $service = new Service(new ProjectService(new HistoryService(), CurrentUser::get()));

        return [
            Service::SCOPE => [
                'project.list' => [
                    'callback' => $service->getList(...)
                ],
                'project.get' => [
                    'callback' => $service->get(...)
                ]
            ]
        ];
    }

    /**
     * @throws RestException
     */
    public function get(array $parameters, $navigation, CRestServer $server): array
    {
        if (!isset($parameters['ID'])) {
            throw new RestException('Argument "ID" is not defined', RestException::ERROR_ARGUMENT);
        }

        $projectId = (int)$parameters['ID'];
        if ($projectId < 0) {
            throw new RestException('Argument "ID" MUST be a positive integer', RestException::ERROR_ARGUMENT);
        }

        try {
            $project = $this->projectService->getById($projectId);
        } catch (NotFoundException $e) {
            throw new RestException($e->getMessage(), RestException::ERROR_NOT_FOUND);
        } catch (ServiceException $e) {
            throw new RestException($e->getMessage(), previous: $e);
        }
        return $this->convertProject($project);
    }

    protected function convertProject(Project $project): array
    {
        return [
            'ID' => $project->id,
            'TITLE' => $project->title,
            'CREATED_AT' => CRestUtil::ConvertDateTime($project->createdAt),
            'CREATED_BY' => $project->createdBy,
            'UPDATED_AT' => CRestUtil::ConvertDateTime($project->updatedAt),
            'UPDATED_BY' => $project->updatedBy,
            'COMPLETION_DATE' => CRestUtil::ConvertDateTime($project->completionDate),
            'ESTIMATED_COMPLETION_DATE' => CRestUtil::ConvertDateTime($project->estimatedCompletionDate),
            'DESCRIPTION' => $project->description,
            'RESPONSIBLE_ID' => $project->responsibleId,
            'COMMENT' => $project->comment,
            'INCOME' => $project->income,
        ];
    }

    /**
     * @throws RestException
     */
    public function getList(array $parameters, $navigation, CRestServer $server): array
    {
        $filter = $parameters['filter'] ?? [];
        $order = $parameters['order'] ?? ['ID' => 'DESC'];

        $filter['CREATED_AT'] = CRestUtil::unConvertDateTime($filter['CREATED_AT']);
        $filter['ESTIMATED_COMPLETION_DATE'] = CRestUtil::unConvertDateTime($filter['ESTIMATED_COMPLETION_DATE']);
        $filter['COMPLETION_DATE'] = CRestUtil::unConvertDateTime($filter['COMPLETION_DATE']);

        $createdAt = DateRange::createFromArray($filter, 'CREATED_AT');
        $estimatedCompletionDate = DateRange::createFromArray($filter, 'ESTIMATED_COMPLETION_DATE');
        $completionDate = DateRange::createFromArray($filter, 'COMPLETION_DATE');

        $filter = new Filter(
            $filter['TITLE'] ?? null,
            $createdAt,
            (array)($filter['RESPONSIBLE_ID'] ?? null),
            $estimatedCompletionDate,
            $completionDate,
        );

        $navigationParameters = Service::getNavData($navigation, true);
        $pageNumber = (int)($navigationParameters['offset'] / $navigationParameters['limit'] + 1);

        try {
            $fragment = $this->projectService->getFragment(
                $filter,
                $order,
                $navigationParameters['limit'],
                $pageNumber
            );

            return Service::setNavData(
                [
                    'projects' => $fragment->map($this->convertProject(...)),
                ],
                [
                    'offset' => $navigationParameters['offset'],
                    'count' => $this->projectService->count($filter)
                ]
            );
        } catch (ServiceException $e) {
            throw new RestException($e->getMessage(), previous: $e);
        }
    }

    protected static function setNavData($result, $dbRes): array
    {
        /** @var array $result */
        $result = parent::setNavData($result, $dbRes);

        return $result;
    }
}