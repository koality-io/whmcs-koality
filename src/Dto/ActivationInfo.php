<?php

declare(strict_types=1);

namespace WHMCS\Module\Server\Koality\Dto;

use Assert\Assert;

final class ActivationInfo
{
    private string $uid;
    private bool $activated;

    /**
     * @param array<string, string> $data
     */
    public function __construct(array $data)
    {
        Assert::that($data)->keyExists('uid');
        Assert::that($data)->keyExists('activated');

        $this->uid = $data['uid'];
        $this->activated = (bool) $data['activated'];
    }

    public function getUid(): string
    {
        return $this->uid;
    }

    public function isActivated(): bool
    {
        return $this->activated;
    }
}
