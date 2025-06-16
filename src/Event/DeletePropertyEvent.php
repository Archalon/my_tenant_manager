<?php

namespace App\Event;

use App\Entity\Property;
use Symfony\Contracts\EventDispatcher\Event;

class DeletePropertyEvent extends Event
{
    public const NAME = 'property.delete';

    private Property $property;

    public function __construct(Property $property)
    {
        $this->property = $property;
    }

    public function getProperty(): Property
    {
        return $this->property;
    }
}