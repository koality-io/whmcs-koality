<?php

declare(strict_types=1);

namespace WHMCS\Module\Server\Koality;

final class ProductOptions
{
    public const PLAN_ID = 'configoption1';
    public const DOMAIN = 'configoption2';
    public const ADDITIONAL_SINGLE_PROJECT = 'configoption3';
    public const ADDITIONAL_THIRTY_PROJECTS = 'configoption4';

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
