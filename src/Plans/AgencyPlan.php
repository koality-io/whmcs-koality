<?php

// Copyright 2024. WebPros International GmbH. All rights reserved.

declare(strict_types=1);

namespace WHMCS\Module\Server\Koality\Plans;

use WHMCS\Module\Server\Koality\Plan;

final class AgencyPlan implements Plan
{
    public function getId(): string
    {
        return 'agency';
    }

    public function getName(): string
    {
        return 'Agency';
    }

    public function getPlanApiConst(): string
    {
        return 'KOA-AGC-30-1M';
    }

    public function getSingleAdditionalProjectApiConst(): string
    {
        return 'KOA-PRJ-ADD-1-1M';
    }

    public function getThirtyAdditionalProjectsApiConst(): string
    {
        return 'KOA-PRJ-ADD-30-1M';
    }
}
