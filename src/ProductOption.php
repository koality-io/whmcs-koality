<?php

// Copyright 2024. WebPros International GmbH. All rights reserved.

declare(strict_types=1);

namespace WHMCS\Module\Server\Koality;

final class ProductOption
{
    public const PLAN_ID = 'configoption1';
    public const DOMAIN_APPLICATION = 'configoption2';
    public const DOMAIN_LICENSE_ACTIVATION = 'configoption3';
    public const ADDITIONAL_SINGLE_PROJECT = 'configoption4';
    public const ADDITIONAL_THIRTY_PROJECTS = 'configoption5';

    private const OPTION_ADDITIONAL_SINGLE_PROJECT = 'additional_single_projects';
    private const OPTION_ADDITIONAL_THIRTY_PROJECTS = 'additional_thirty_projects';

    /**
     * @param array<array<string, mixed>> $params
     */
    public static function additionalSingleProjectAllowance(array $params): int
    {
        $additionalSingleProjects = (int) $params[self::ADDITIONAL_SINGLE_PROJECT];

        if (isset($params['configoptions'][self::OPTION_ADDITIONAL_SINGLE_PROJECT])) {
            $additionalSingleProjects += (int) $params['configoptions'][self::OPTION_ADDITIONAL_SINGLE_PROJECT];
        }

        return $additionalSingleProjects;
    }

    /**
     * @param array<array<string, mixed>> $params
     */
    public static function additionalThirtyProjectsAllowance(array $params): int
    {
        $additionalThirtyProjects = (int) $params[self::ADDITIONAL_THIRTY_PROJECTS];

        if (isset($params['configoptions'][self::OPTION_ADDITIONAL_THIRTY_PROJECTS])) {
            $additionalThirtyProjects += (int) $params['configoptions'][self::OPTION_ADDITIONAL_THIRTY_PROJECTS];
        }

        return $additionalThirtyProjects;
    }
}
