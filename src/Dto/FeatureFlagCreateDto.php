<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class FeatureFlagCreateDto
{
    #[Assert\NotBlank]
    public string $name;

    #[Assert\NotBlank]
    public bool $isActive;

    #[Assert\NotBlank]
    public string $tenantCode;
}