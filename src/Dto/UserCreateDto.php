<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class UserCreateDto
{
    #[Assert\NotBlank]
    public string $email;

    #[Assert\NotBlank]
    public string $username;

    #[Assert\NotBlank]
    #[Assert\Length(min: 6)]
    public string $password;

    #[Assert\Type('bool')]
    public bool $isActive = true;

    #[Assert\Type('array')]
    public array $roles = [];
}

