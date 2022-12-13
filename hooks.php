<?php

declare(strict_types=1);

// Copyright 2022. Plesk International GmbH. All rights reserved.

// This file is only kept here to overwrite older versions to prevent module errors

use Illuminate\Database\Capsule\Manager as Capsule;
use WHMCS\Module\Server\Koality\Constants;
use WHMCS\Module\Server\Koality\Exception\CouldNotCheckAndAddCustomFields;
use WHMCS\Module\Server\Koality\Logger;
use WHMCS\Module\Server\Koality\Service\AddRequiredCustomFields;

require __DIR__ . '/vendor/autoload.php';

add_hook('ProductEdit', 1, static function ($vars) {
    $pid = $vars['pid'] ?? null;
    $servertype = $vars['servertype'] ?? null;

    $loggerName = 'CheckAndAddCustomFieldsForKoality';

    if ($pid === null) {
        Logger::error($loggerName, $vars, CouldNotCheckAndAddCustomFields::becausePidIsMissing());

        return [];
    }

    if ($servertype === null) {
        Logger::error($loggerName, $vars, CouldNotCheckAndAddCustomFields::becauseServertypeIsMissing());

        return [];
    }

    if ($servertype !== Constants::MODULE_NAME) {
        Logger::log(
            $loggerName,
            sprintf(
                'Skip check and adding required custom fields for product with id "%s" because it is not from type "koality".',
                $pid
            ),
            ''
        );

        return [];
    }

    $addRequiredCustomFields = new AddRequiredCustomFields(Capsule::connection());

    ($addRequiredCustomFields)($pid);

    return [];
});
