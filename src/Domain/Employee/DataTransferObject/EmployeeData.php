<?php

namespace Domain\Employee\DataTransferObject;

/**
 * Class EmployeeData
 * @package Domain\Employee\DataTransferObject
 * @property string $firstName
 * @property string|null $lastName
 */
class EmployeeData
{
    public string $firstName;
    public ?string $lastName;

    public function __construct(string $firstName, ?string $lastName)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public static function fromRequest(array $data): self
    {
        return new self(
            $data['first_name'],
            $data['last_name'] ?? null
        );
    }

}
