<?php

// Copyright 2024. WebPros International GmbH. All rights reserved.

declare(strict_types=1);

namespace WHMCS\Module\Server\Koality\Dto;

use Assert\Assert;

final class License
{
    private string $ownerId;
    private string $status;
    private bool $terminated;
    private bool $suspended;
    private KeyIdentifiers $keyIdentifiers;
    private ActivationInfo $activationInfo;

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(array $data)
    {
        $keyIdentifiers = $data['keyIdentifiers'] ?? [];
        $activationInfo = $data['activationInfo'] ?? [];

        Assert::that($data)->keyExists('ownerId');
        Assert::that($data)->keyExists('status');
        Assert::that($data)->keyExists('terminated');
        Assert::that($data)->keyExists('suspended');
        Assert::that($keyIdentifiers)->isArray();
        Assert::that($activationInfo)->isArray();

        $this->ownerId = $data['ownerId'];
        $this->status = $data['status'];
        $this->terminated = (bool) $data['terminated'];
        $this->suspended = (bool) $data['suspended'];

        $this->keyIdentifiers = new KeyIdentifiers($keyIdentifiers);
        $this->activationInfo = new ActivationInfo($activationInfo);
    }

    public function getOwnerId(): string
    {
        return $this->ownerId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function isTerminated(): bool
    {
        return $this->terminated;
    }

    public function isSuspended(): bool
    {
        return $this->suspended;
    }

    public function getKeyIdentifiers(): KeyIdentifiers
    {
        return $this->keyIdentifiers;
    }

    public function getActivationInfo(): ActivationInfo
    {
        return $this->activationInfo;
    }
}
