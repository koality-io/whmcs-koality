<?php

declare(strict_types=1);

// Copyright 2022. Plesk International GmbH. All rights reserved.

// This file is only kept here to overwrite older versions to prevent module errors

use Illuminate\Database\Capsule\Manager as Capsule;
use WHMCS\Module\Server\Koality\Constants;
use WHMCS\Module\Server\Koality\CustomFields;
use WHMCS\Module\Server\Koality\Exception\CouldNotCheckAndAddCustomFields;
use WHMCS\Module\Server\Koality\Logger;

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

    $customFields = Capsule::table('tblcustomfields')
        ->where('relid', '=', $pid)
        ->get()
    ;

    $hasFieldKeyId = false;
    $hasFieldActivationCode = false;
    $hasFieldActivationUrl = false;

    foreach ($customFields as $customField) {
        if ($customField->fieldname === CustomFields::KEY_ID) {
            $hasFieldKeyId = true;

            continue;
        }

        if ($customField->fieldname === CustomFields::ACTIVATION_CODE) {
            $hasFieldActivationCode = true;

            continue;
        }

        if ($customField->fieldname === CustomFields::ACTIVATION_URL) {
            $hasFieldActivationUrl = true;

            continue;
        }
    }

    $nowDate = date('Y-m-d H:i:s');

    if (!$hasFieldKeyId) {
        Capsule::table('tblcustomfields')
            ->insert([
                'type' => 'product',
                'relid' => $pid,
                'fieldname' => CustomFields::KEY_ID,
                'fieldtype' => 'text',
                'adminonly' => 'on',
                'created_at' => $nowDate,
                'updated_at' => $nowDate,
            ])
        ;
    }

    if (!$hasFieldActivationCode) {
        Capsule::table('tblcustomfields')
            ->insert([
                'type' => 'product',
                'relid' => $pid,
                'fieldname' => CustomFields::ACTIVATION_CODE,
                'fieldtype' => 'text',
                'adminonly' => 'on',
                'created_at' => $nowDate,
                'updated_at' => $nowDate,
            ])
        ;
    }

    if (!$hasFieldActivationUrl) {
        Capsule::table('tblcustomfields')
            ->insert([
                'type' => 'product',
                'relid' => $pid,
                'fieldname' => CustomFields::ACTIVATION_URL,
                'fieldtype' => 'text',
                'adminonly' => 'on',
                'created_at' => $nowDate,
                'updated_at' => $nowDate,
            ])
        ;
    }

    return [];
});
