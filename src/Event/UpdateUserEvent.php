<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;
use App\Entity\User;

class UpdateUserEvent extends Event
{
    public const NAME = 'user.update';

    private User $user;
    private array $changes;

    public function __construct(User $user, array $changes)
    {
        $this->user = $user;
        $this->changes = $changes;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getChanges(): array
    {
        return $this->changes;
    }
}