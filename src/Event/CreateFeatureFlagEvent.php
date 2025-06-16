<?php

namespace App\Event;

use App\Entity\FeatureFlag;
use Symfony\Contracts\EventDispatcher\Event;

class CreateFeatureFlagEvent extends Event
{
    public const NAME = 'featureflag.create';

    private FeatureFlag $featureFlag;
    private array $changes;

    public function __construct(FeatureFlag $featureFlag, array $changes = [])
    {
        $this->featureFlag = $featureFlag;
        $this->changes = $changes;
    }

    public function getFeatureFlag(): FeatureFlag
    {
        return $this->featureFlag;
    }

    public function getChanges(): array
    {
        return $this->changes;
    }
}