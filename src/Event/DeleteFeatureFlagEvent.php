<?php

namespace App\Event;

use App\Entity\FeatureFlag;
use Symfony\Contracts\EventDispatcher\Event;

class DeleteFeatureFlagEvent extends Event
{
    public const NAME = 'featureflag.delete';

    private FeatureFlag $featureFlag;

    public function __construct(FeatureFlag $featureFlag)
    {
        $this->featureFlag = $featureFlag;
    }

    public function getFeatureFlag(): FeatureFlag
    {
        return $this->featureFlag;
    }
}