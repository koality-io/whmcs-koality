<?php
/*
 * Copyright 2022. Plesk International GmbH. All rights reserved.
 */

declare(strict_types=1);

namespace WHMCS\Module\Server\Koality\Plans;

use WHMCS\Module\Server\Koality\Plan;

final class SinglePlan implements Plan
{
    public function getId(): string
    {
        return 'single';
    }

    public function getName(): string
    {
        return 'Koality';
    }

    public function getPlanApiConst(): string
    {
        return 'KOA-PRJ-1-1M';
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
