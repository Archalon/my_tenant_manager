<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;
use App\Entity\User;

class AuditLogEvent extends Event
{
    public const NAME = 'audit.log';
    private User $user;
    private string $title;
    private string $resourceType;
    private string $resourceId;
    private string $action;
    private array $context;

    public function __construct(User $user, string $title, string $resourceType, string $resourceId, string $action, array $context = [])
    {
        $this->user = $user;
        $this->title = $title;
        $this->resourceType = $resourceType;
        $this->resourceId = $resourceId;
        $this->action = $action;
        $this->context = $context;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getResourceType(): string
    {
        return $this->resourceType;
    }

    public function getResourceId(): string
    {
        return $this->resourceId;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getContext(): array
    {
        return $this->context;
    }
}