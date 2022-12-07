<?php

declare(strict_types=1);

// Copyright 2022. Plesk International GmbH. All rights reserved.

if (!defined('WHMCS')) {
    exit('This file cannot be accessed directly');
}

require __DIR__ . '/vendor/autoload.php';

use WHMCS\Module\Server\Koality\CustomFields;
use WHMCS\Module\Server\Koality\KaApi;
use WHMCS\Module\Server\Koality\Logger;
use WHMCS\Module\Server\Koality\PlanCollection;
use WHMCS\Module\Server\Koality\Plans\SinglePlan;
use WHMCS\Module\Server\Koality\ProductOptions;
use WHMCS\Module\Server\Koality\ServerOptions;
use WHMCS\Module\Server\Koality\Translator;
use WHMCS\Module\Server\Koality\UrlHelper;

function koality_getKaApiClient(array $params): KaApi
{
    return new KaApi(
        $params[ServerOptions::SERVER_SCHEME],
        $params[ServerOptions::SERVER_HOST],
        (int) $params[ServerOptions::SERVER_PORT],
        $params[ServerOptions::SERVER_USERNAME],
        $params[ServerOptions::SERVER_PASSWORD]
    );
}

function koality_MetaData(): array
{
    return [
        'DisplayName' => 'Koality',
        'APIVersion' => '1.1',
        'RequiresServer' => true,
        'ServiceSingleSignOnLabel' => false,
    ];
}

function koality_ConfigOptions(): array
{
    global $CONFIG;

    $plans = new PlanCollection();
    $planOptions = [];

    foreach ($plans->getAll() as $plan) {
        $planOptions[$plan->getId()] = $plan->getName();
    }

    $proPlan = new SinglePlan();
    $translator = Translator::getInstance($CONFIG);

    return [
        ProductOptions::PLAN_ID => [
            'FriendlyName' => $translator->translate('koality_label_plan'),
            'Type' => 'dropdown',
            'Size' => '25',
            'Options' => $planOptions,
            'Default' => $proPlan->getId(),
            'SimpleMode' => true,
        ],
        ProductOptions::DOMAIN => [
            'FriendlyName' => $translator->translate('koality_label_domain'),
            'Type' => 'text',
            'Size' => '25',
            'Default' => '',
            'SimpleMode' => true,
        ],
        ProductOptions::ADDITIONAL_SINGLE_PROJECT => [
            'FriendlyName' => $translator->translate('koality_label_additional_single_projects'),
            'Type' => 'text',
            'Size' => '25',
            'Default' => '',
            'SimpleMode' => true,
        ],
        ProductOptions::ADDITIONAL_THIRTY_PROJECTS => [
            'FriendlyName' => $translator->translate('koality_label_additional_thirty_projects'),
            'Type' => 'text',
            'Size' => '25',
            'Default' => '',
            'SimpleMode' => true,
        ],
    ];
}

function koality_ClientArea(array $params): string
{
    global $CONFIG;

    $kaApi = koality_getKaApiClient($params);
    $keyId = $params['customfields'][CustomFields::KEY_ID];
    $translator = Translator::getInstance($CONFIG);

    try {
        $license = $kaApi->retrieveLicense($keyId);
        $domain = $params[ProductOptions::DOMAIN];
        $activationUrl = UrlHelper::getActivationUrl($license, $domain);
        $dashboardUrl = UrlHelper::getDashboardUrl($domain);

        if ($license->getActivationInfo()->isActivated()) {
            return '<div class="tab-content"><div class="row"><div class="col-sm-3 text-left">' . $translator->translate('koality_button_license_activated') . '</div></div></div><br/>';
        }

        $html = '';

        if (!$license->isTerminated() && !$license->isSuspended()) {
            $html .= '<div class="tab-content"><a class="btn btn-block btn-info" href="' . $activationUrl . '" target="_blank">' . $translator->translate('koality_button_activate_license') . '</a></div><br/>';
        }

        $html .= '<div class="tab-content"><a class="btn btn-block btn-default" href="' . $dashboardUrl . '" target="_blank">' . $translator->translate('koality_button_dashboard') . '</a></div><br/>';

        return $html;
    } catch (Throwable $exception) {
        Logger::error(__FUNCTION__, $params, $exception);

        return $exception->getMessage();
    }
}

function koality_CreateAccount(array $params): string
{
    try {
        $quantityOfSingleAdditionalProjects = ProductOptions::additionalSingleProjectAllowance($params);
        $quantityOfThirtyAdditionalProjects = ProductOptions::additionalThirtyProjectsAllowance($params);
        $plans = new PlanCollection();
        $plan = $plans->getPlanById($params[ProductOptions::PLAN_ID]);
        $kaApi = koality_getKaApiClient($params);
        $license = $kaApi->createLicense($plan, $quantityOfSingleAdditionalProjects, $quantityOfThirtyAdditionalProjects);
        $domain = $params[ProductOptions::DOMAIN];

        $params['model']->serviceProperties->save([
            CustomFields::KEY_ID => $license->getKeyIdentifiers()->getKeyId(),
            CustomFields::ACTIVATION_CODE => $license->getKeyIdentifiers()->getActivationCode(),
            CustomFields::ACTIVATION_URL => UrlHelper::getActivationUrl($license, $domain),
        ]);

        return 'success';
    } catch (Throwable $exception) {
        Logger::error(__FUNCTION__, $params, $exception);

        return $exception->getMessage();
    }
}

function koality_SuspendAccount(array $params): string
{
    try {
        $keyId = $params['customfields'][CustomFields::KEY_ID];
        $kaApi = koality_getKaApiClient($params);

        $kaApi->suspendLicense($keyId);

        return 'success';
    } catch (Throwable $exception) {
        Logger::error(__FUNCTION__, $params, $exception);

        return $exception->getMessage();
    }
}

function koality_UnsuspendAccount(array $params): string
{
    try {
        $keyId = $params['customfields'][CustomFields::KEY_ID];
        $kaApi = koality_getKaApiClient($params);

        $kaApi->resumeLicense($keyId);

        return 'success';
    } catch (Throwable $exception) {
        Logger::error(__FUNCTION__, $params, $exception);

        return $exception->getMessage();
    }
}

function koality_TerminateAccount(array $params): string
{
    try {
        $keyId = $params['customfields'][CustomFields::KEY_ID];
        $kaApi = koality_getKaApiClient($params);

        $kaApi->terminateLicense($keyId);

        return 'success';
    } catch (Throwable $exception) {
        Logger::error(__FUNCTION__, $params, $exception);

        return $exception->getMessage();
    }
}

function koality_ChangePackage(array $params): string
{
    try {
        $keyId = $params['customfields'][CustomFields::KEY_ID];
        $quantityOfSingleAdditionalProjects = ProductOptions::additionalSingleProjectAllowance($params);
        $quantityOfThirtyAdditionalProjects = ProductOptions::additionalThirtyProjectsAllowance($params);
        $plans = new PlanCollection();
        $plan = $plans->getPlanById($params[ProductOptions::PLAN_ID]);
        $kaApi = koality_getKaApiClient($params);

        $kaApi->modifyLicense($keyId, $plan, $quantityOfSingleAdditionalProjects, $quantityOfThirtyAdditionalProjects);

        return 'success';
    } catch (Throwable $exception) {
        Logger::error(__FUNCTION__, $params, $exception);

        return $exception->getMessage();
    }
}

function koality_TestConnection(array $params): array
{
    try {
        $kaApi = koality_getKaApiClient($params);

        $kaApi->testConnection();

        return [
            'success' => true,
            'error' => '',
        ];
    } catch (Throwable $exception) {
        Logger::error(__FUNCTION__, $params, $exception);

        return [
            'success' => false,
            'error' => $exception->getMessage(),
        ];
    }
}
