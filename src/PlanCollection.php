<?php

declare(strict_types=1);

// Copyright 2022. Plesk International GmbH. All rights reserved.

namespace WHMCS\Module\Server\Koality;

use Assert\Assert;
use WHMCS\Module\Server\Koality\Plans\AgencyPlan;
use WHMCS\Module\Server\Koality\Plans\SinglePlan;

final class PlanCollection
{
    /**
     * @var Plan[]
     */
    private array $plans = [];

    public function __construct()
    {
        $singlePlan = new SinglePlan();
        $agencyPlan = new AgencyPlan();

        $this->plans[$singlePlan->getId()] = $singlePlan;
        $this->plans[$agencyPlan->getId()] = $agencyPlan;
    }

    /**
     * @return Plan[]
     */
    public function getAll(): array
    {
        return $this->plans;
    }

    public function getPlanById(string $id): Plan
    {
        Assert::that($this->plans)->keyExists($id, "Plan with id '{$id}' not found");

        return $this->plans[$id];
    }
}
