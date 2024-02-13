<?php

// Copyright 2024. WebPros International GmbH. All rights reserved.

declare(strict_types=1);

namespace WHMCS\Module\Server\Koality;

interface Plan
{
    public function getId(): string;

    public function getName(): string;

    public function getPlanApiConst(): string;

    public function getSingleAdditionalProjectApiConst(): string;

    public function getThirtyAdditionalProjectsApiConst(): string;
}
