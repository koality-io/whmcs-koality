<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\DowngradeLevelSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/../lang',
        __DIR__ . '/../src',
        __DIR__ . '/../vendor',
        __DIR__ . '/../hooks.php',
        __DIR__ . '/../koality.php',
    ]);

    $rectorConfig->sets([
        DowngradeLevelSetList::DOWN_TO_PHP_73
    ]);
};
