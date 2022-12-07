<?php

declare(strict_types=1);

// Copyright 2022. Plesk International GmbH. All rights reserved.

namespace WHMCS\Module\Server\Koality;

use WHMCS\Module\Server\Koality\Dto\License;

final class UrlHelper
{
    private const DEFAULT_DASHBOARD_URL = 'https://koality.360monitoring.com/';

    public static function getActivationUrl(License $license, string $domain): string
    {
        if ($domain === '') {
            return $license->getKeyIdentifiers()->getActivationLink();
        }

        return 'https://' . $domain . '/license/activate/' . $license->getKeyIdentifiers()->getActivationCode();
    }

    public static function getDashboardUrl(string $domain): string
    {
        if ($domain === '') {
            return self::DEFAULT_DASHBOARD_URL;
        }

        return 'https://' . $domain . '/';
    }
}
