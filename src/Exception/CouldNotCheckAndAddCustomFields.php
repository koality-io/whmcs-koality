<?php

// Copyright 2024. WebPros International GmbH. All rights reserved.

declare(strict_types=1);

namespace WHMCS\Module\Server\Koality\Exception;

final class CouldNotCheckAndAddCustomFields extends \RuntimeException
{
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function becausePidIsMissing(): self
    {
        return new self('Could not add required custom fields because the "pid" is missing in $vars.');
    }

    public static function becauseServertypeIsMissing(): self
    {
        return new self('Could not add required custom fields because the "servertype" is missing in $vars.');
    }
}
