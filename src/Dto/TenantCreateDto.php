<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class TenantCreateDto
{
    #[Assert\NotBlank]
    public string $name;

    #[Assert\NotBlank]
    public string $code;

    #[Assert\Choice(['created', 'ready_for_provisioning', 'provisioning', 'ready'])]
    public string $status = 'created';

    #[Assert\Type('bool')]
    public bool $isActive = true;
}
