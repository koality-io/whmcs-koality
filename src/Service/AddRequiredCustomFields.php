<?php
/*
 * Copyright 2022. Plesk International GmbH. All rights reserved.
 */

declare(strict_types=1);

namespace WHMCS\Module\Server\Koality\Service;

use Illuminate\Database\ConnectionInterface;
use WHMCS\Module\Server\Koality\ServiceProperty;

final class AddRequiredCustomFields
{
    private ConnectionInterface $databaseConnection;

    public function __construct(ConnectionInterface $databaseConnection)
    {
        $this->databaseConnection = $databaseConnection;
    }

    public function __invoke(int $pid): void
    {
        $customFields = $this->databaseConnection->table('tblcustomfields')
            ->where('relid', '=', $pid)
            ->get()
        ;

        $hasFieldKeyId = false;
        $hasFieldActivationCode = false;
        $hasFieldActivationUrl = false;

        $nowDate = date('Y-m-d H:i:s');

        foreach ($customFields as $customField) {
            if ($customField->fieldname === ServiceProperty::KEY_ID) {
                $hasFieldKeyId = true;

                continue;
            }

            if ($customField->fieldname === ServiceProperty::ACTIVATION_CODE) {
                $hasFieldActivationCode = true;

                continue;
            }

            if ($customField->fieldname === ServiceProperty::ACTIVATION_URL) {
                $hasFieldActivationUrl = true;

                continue;
            }
        }

        if (!$hasFieldKeyId) {
            $this->createAndSaveCustomField($pid, ServiceProperty::KEY_ID, $nowDate);
        }

        if (!$hasFieldActivationCode) {
            $this->createAndSaveCustomField($pid, ServiceProperty::ACTIVATION_CODE, $nowDate);
        }

        if (!$hasFieldActivationUrl) {
            $this->createAndSaveCustomField($pid, ServiceProperty::ACTIVATION_URL, $nowDate);
        }
    }

    private function createAndSaveCustomField(int $pid, string $fieldName, string $nowDate): void
    {
        $this->databaseConnection->table('tblcustomfields')
            ->insert([
                'type' => 'product',
                'relid' => $pid,
                'fieldname' => $fieldName,
                'fieldtype' => 'text',
                'adminonly' => 'on',
                'created_at' => $nowDate,
                'updated_at' => $nowDate,
            ])
        ;
    }
}
