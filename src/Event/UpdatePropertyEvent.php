<?php

namespace App\Event;

use App\Entity\Property;
use Symfony\Contracts\EventDispatcher\Event;

class UpdatePropertyEvent extends Event
{
    public const NAME = 'property.update';

    private Property $property;
    private array $changes;

    public function __construct(Property $property, array $changes)
    {
        $this->property = $property;
        $this->changes = $changes;
    }

    public function getProperty(): Property
    {
        return $this->property;
    }

    public function getChanges(): array
    {
        return $this->changes;
    }
}