<?php

namespace Domain\Employee\Controllers;

use App\Http\Controllers\Controller;
use Domain\Employee\Controllers\Requests\EmployeeSearchRequest;
use Domain\Employee\Controllers\Responses\EmployeeResource;
use Domain\Employee\DataTransferObject\EmployeeData;
use Domain\Employee\Models\Employee;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EmployeeController extends Controller
{
    public function search(EmployeeSearchRequest $request): AnonymousResourceCollection
    {
        $dto = EmployeeData::fromRequest($request->validated());

        $employees = Employee::query()->searchByName($dto)
            ->with([
                'jobTitle',
                'employmentType',
                'department'
            ])->get();

        return EmployeeResource::collection($employees);
    }
}
