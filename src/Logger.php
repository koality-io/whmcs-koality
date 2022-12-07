<?php

declare(strict_types=1);

// Copyright 2022. Plesk International GmbH. All rights reserved.

namespace WHMCS\Module\Server\Koality;

final class Logger
{
    /**
     * @param string|array<mixed> $request
     * @param string|array<mixed> $response
     */
    public static function log(string $function, $request, $response): void
    {
        logModuleCall(
            Constants::MODULE_NAME,
            $function,
            $request,
            $response
        );
    }

    /**
     * @param string|array<mixed> $request
     */
    public static function error(string $function, $request, \Throwable $exception): void
    {
        self::log($function, $request, (string) $exception);
    }
}
