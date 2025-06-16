<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class PropertyCreateDto
{
    #[Assert\NotBlank]
    public string $name;

    #[Assert\NotBlank]
    public string $value;

    #[Assert\NotBlank]
    #[Assert\Choice(['int', 'bool', 'array', 'string', 'float'])]
    public string $type;

    #[Assert\Type('bool')]
    public bool $isConfidential = false;

    #[Assert\Type('bool')]
    public bool $isActive = true;

    #[Assert\NotBlank]
    public string $tenantCode;

    public function __construct(string $name, string $value, string $type, bool $isActive, bool $isConfidential, string $tenantCode)
    {
        $this->name = $name;
        $this->value = $value;
        $this->type = $type;
        $this->isActive = $isActive;
        $this->isConfidential = $isConfidential;
        $this->tenantCode = $tenantCode;
    }
}