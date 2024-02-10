<?php

namespace Domain\Employee\Builders;

use Domain\Employee\DataTransferObject\EmployeeData;
use Illuminate\Database\Eloquent\Builder;

class EmployeeBuilder extends Builder
{
    public function searchByName(EmployeeData $dto): EmployeeBuilder
    {
        return $this->where('first_name', $dto->firstName)
            ->when($dto->lastName, function ($query) use ($dto) {
                $query->where('last_name', $dto->lastName);
            });
    }

}
