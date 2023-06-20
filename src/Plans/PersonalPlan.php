<?php
/*
 * Copyright 2022. Plesk International GmbH. All rights reserved.
 */

declare(strict_types=1);

namespace WHMCS\Module\Server\Koality\Plans;

use WHMCS\Module\Server\Koality\Plan;

final class PersonalPlan implements Plan
{
    public function getId(): string
    {
        return 'personal';
    }

    public function getName(): string
    {
        return 'Personal';
    }

    public function getPlanApiConst(): string
    {
        return 'KOA-BSC-PRJ-1-1M';
    }

    public function getSingleAdditionalProjectApiConst(): string
    {
        return 'KOA-BSC-PRJ-ADD-1-1M';
    }

    public function getThirtyAdditionalProjectsApiConst(): string
    {
        return '';
    }
}
