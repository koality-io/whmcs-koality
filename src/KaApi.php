<?php

declare(strict_types=1);

// Copyright 2022. Plesk International GmbH. All rights reserved.

namespace WHMCS\Module\Server\Koality;

use GuzzleHttp\Client;
use WHMCS\Module\Server\Koality\Dto\License;

final class KaApi
{
    private const CONTENT_TYPE_JSON = 'application/json';

    private Client $client;

    public function __construct(
        string $scheme,
        string $host,
        int $port,
        string $username,
        string $password
    ) {
        $baseUri = $scheme . '://' . $host;

        if ($port > 0) {
            $baseUri .= ':' . $port;
        }

        $this->client = new Client([
            'base_uri' => $baseUri,
            'auth' => [$username, $password],
            'headers' => [
                'Accept' => self::CONTENT_TYPE_JSON,
                'Content-Type' => self::CONTENT_TYPE_JSON,
            ],
        ]);
    }

    public function testConnection(): bool
    {
        $response = $this->client->get('/jsonrest/business-partner/30/keys');

        $response->getBody()->getContents();

        return true;
    }

    public function createLicense(Plan $plan, int $quantityOfSingleAdditionalProjects, int $quantityOfThirtyAdditionalProjects): License
    {
        $options = [
            'json' => [
                'items' => $this->buildItems($plan, $quantityOfSingleAdditionalProjects, $quantityOfThirtyAdditionalProjects),
            ],
        ];

        $response = $this->client->post('/jsonrest/business-partner/30/keys?return-key-state=yes', $options);
        $data = json_decode($response->getBody()->getContents(), true, 512, \JSON_THROW_ON_ERROR);

        return new License($data);
    }

    public function retrieveLicense(string $keyId): License
    {
        $response = $this->client->get("/jsonrest/business-partner/30/keys/{$keyId}?return-key-state=yes");
        $data = json_decode($response->getBody()->getContents(), true, 512, \JSON_THROW_ON_ERROR);

        return new License($data);
    }

    public function suspendLicense(string $keyId): License
    {
        $options = [
            'json' => [
                'suspended' => 'true',
            ],
        ];

        $response = $this->client->put("/jsonrest/business-partner/30/keys/{$keyId}?return-key-state=yes", $options);
        $data = json_decode($response->getBody()->getContents(), true, 512, \JSON_THROW_ON_ERROR);

        return new License($data);
    }

    public function resumeLicense(string $keyId): License
    {
        $options = [
            'json' => [
                'suspended' => 'false',
            ],
        ];

        $response = $this->client->put("/jsonrest/business-partner/30/keys/{$keyId}?return-key-state=yes", $options);
        $data = json_decode($response->getBody()->getContents(), true, 512, \JSON_THROW_ON_ERROR);

        return new License($data);
    }

    public function terminateLicense(string $keyId): License
    {
        $response = $this->client->delete("/jsonrest/business-partner/30/keys/{$keyId}?return-key-state=yes");
        $data = json_decode($response->getBody()->getContents(), true, 512, \JSON_THROW_ON_ERROR);

        return new License($data);
    }

    public function modifyLicense(string $keyId, Plan $plan, int $quantityOfSingleAdditionalProjects, int $quantityOfThirtyAdditionalProjects): License
    {
        $options = [
            'json' => [
                'items' => $this->buildItems($plan, $quantityOfSingleAdditionalProjects, $quantityOfThirtyAdditionalProjects),
            ],
        ];

        $response = $this->client->put("/jsonrest/business-partner/30/keys/{$keyId}?return-key-state=yes", $options);
        $data = json_decode($response->getBody()->getContents(), true, 512, \JSON_THROW_ON_ERROR);

        return new License($data);
    }

    /**
     * @see https://docs.plesk.com/en-US/onyx/partner-api-3.0/introduction-to-key-administrator-partner-api-30.77827/
     *
     * @return array<array<string, int|string>>
     */
    private function buildItems(Plan $plan, int $quantityOfSingleAdditionalProjects, int $quantityOfThirtyAdditionalProjects): array
    {
        return [
            [
                'item' => $plan->getPlanApiConst(),
            ],
            [
                'item' => $plan->getSingleAdditionalProjectApiConst(),
                'quantity' => $quantityOfSingleAdditionalProjects,
            ],
            [
                'item' => $plan->getThirtyAdditionalProjectsApiConst(),
                'quantity' => $quantityOfThirtyAdditionalProjects,
            ],
        ];
    }
}
