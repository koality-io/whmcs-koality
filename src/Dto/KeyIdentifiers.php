<?php

declare(strict_types=1);

namespace WHMCS\Module\Server\Koality\Dto;

use Assert\Assert;

final class KeyIdentifiers
{
    private int $keyId;
    private string $keyNumber;
    private string $activationCode;
    private string $activationLink;

    /**
     * @param array<string, int|string> $data
     */
    public function __construct(array $data)
    {
        Assert::that($data)->keyExists('keyId');
        Assert::that($data)->keyExists('keyNumber');
        Assert::that($data)->keyExists('activationCode');
        Assert::that($data)->keyExists('activationLink');

        Assert::that($data['keyId'])->integer();
        Assert::that($data['keyNumber'])->string();
        Assert::that($data['activationCode'])->string();
        Assert::that($data['activationLink'])->string();

        $this->keyId = $data['keyId'];
        $this->keyNumber = $data['keyNumber'];
        $this->activationCode = $data['activationCode'];
        $this->activationLink = $data['activationLink'];
    }

    public function getKeyId(): int
    {
        return $this->keyId;
    }

    public function getKeyNumber(): string
    {
        return $this->keyNumber;
    }

    public function getActivationCode(): string
    {
        return $this->activationCode;
    }

    public function getActivationLink(): string
    {
        return $this->activationLink;
    }
}
