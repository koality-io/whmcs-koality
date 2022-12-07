<?php

declare(strict_types=1);

// Copyright 2022. Plesk International GmbH. All rights reserved.

namespace WHMCS\Module\Server\Koality;

interface Plan
{
    public function getId(): string;

    public function getName(): string;

    public function getPlanApiConst(): string;

    public function getSingleAdditionalProjectApiConst(): string;

    public function getThirtyAdditionalProjectsApiConst(): string;
}
