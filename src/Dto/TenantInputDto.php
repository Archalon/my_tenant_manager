<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class TenantInputDto
{
    #[Assert\NotBlank]
    public string $dbName;

    #[Assert\NotBlank]
    public string $dbHost;

    #[Assert\NotBlank]
    public string $dbUser;

    #[Assert\NotBlank]
    public string $dbPassword;

    #[Assert\Type('array')]
    public ?array $featureFlags = [];
}