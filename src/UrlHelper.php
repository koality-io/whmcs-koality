<?php

// Copyright 2024. WebPros International GmbH. All rights reserved.

declare(strict_types=1);

namespace WHMCS\Module\Server\Koality;

use WHMCS\Module\Server\Koality\Dto\License;

final class UrlHelper
{
    private const DEFAULT_DASHBOARD_URL = 'https://koality.360monitoring.com/';

    public static function getActivationUrl(License $license, string $domain): string
    {
        if ($domain === '') {
            $url = $license->getKeyIdentifiers()->getActivationLink();
        } else {
            $url = 'https://' . $domain . '/license/activate/' . $license->getKeyIdentifiers()->getActivationCode();
        }

        $query = parse_url($url, PHP_URL_QUERY);

        $url .= ($query === null) ? '?' : '&';
        $url .= 'source=whmcs';

        return $url;
    }

    public static function getDashboardUrl(string $domain): string
    {
        if ($domain === '') {
            return self::DEFAULT_DASHBOARD_URL;
        }

        return 'https://' . $domain . '/';
    }
}
