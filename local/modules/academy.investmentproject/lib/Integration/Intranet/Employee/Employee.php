<?php

namespace Academy\InvestmentProject\Integration\Intranet\Employee;

final class Employee
{
    public function __construct(
        public readonly int $id,
        public readonly ?string $name,
        public readonly ?string $lastName,
        public readonly ?string $secondName,
        public readonly ?string $login,
        public readonly ?string $personalPhotoPath,
        public readonly string $profileUrl,
        public readonly string $formattedName,
        public readonly string $workPosition
    ) {
    }
}