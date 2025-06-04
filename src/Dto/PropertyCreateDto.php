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
}