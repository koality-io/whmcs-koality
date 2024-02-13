<?php

// Copyright 2024. WebPros International GmbH. All rights reserved.

declare(strict_types=1);

namespace WHMCS\Module\Server\Koality;

use Assert\Assert;
use WHMCS\Module\Server\Koality\Plans\AgencyPlan;
use WHMCS\Module\Server\Koality\Plans\BusinessPlan;
use WHMCS\Module\Server\Koality\Plans\PersonalPlan;

final class PlanCollection
{
    /**
     * @var Plan[]
     */
    private array $plans = [];

    public function __construct()
    {
        $personalPlan = new PersonalPlan();
        $businessPlan = new BusinessPlan();
        $agencyPlan = new AgencyPlan();

        $this->plans[$personalPlan->getId()] = $personalPlan;
        $this->plans[$businessPlan->getId()] = $businessPlan;
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
