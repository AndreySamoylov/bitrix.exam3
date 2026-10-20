<?php

namespace Academy\InvestmentProject\Integration\UI;

use Academy\InvestmentProject\Integration\Intranet\Employee\Employee;
use Academy\InvestmentProject\Project;
use CComponentEngine;

final class ValueFormatter
{
    public function formatEmployee(Employee $employee): string
    {
        return sprintf('<a href="%s">%s</a>', $employee->profileUrl, $employee->formattedName);
    }

    public function formatProject(
        Project $project,
        string $urlTemplate,
        string $projectIdPlaceholder = 'INVESTMENT_PROJECT_ID'
    ): string {
        return sprintf(
            '<a href="%s">%s</a>',
            CComponentEngine::makePathFromTemplate($urlTemplate, [$projectIdPlaceholder => $project->id]),
            $project->title
        );
    }
}