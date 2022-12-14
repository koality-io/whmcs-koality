<?php

declare(strict_types=1);

// Copyright 2022. Plesk International GmbH. All rights reserved.

if (!defined('WHMCS')) {
    exit('This file cannot be accessed directly');
}

require __DIR__ . '/vendor/autoload.php';

use WHMCS\Module\Server\Koality\KaApi;
use WHMCS\Module\Server\Koality\Logger;
use WHMCS\Module\Server\Koality\PlanCollection;
use WHMCS\Module\Server\Koality\Plans\SinglePlan;
use WHMCS\Module\Server\Koality\ProductOption;
use WHMCS\Module\Server\Koality\ServerOption;
use WHMCS\Module\Server\Koality\ServiceProperty;
use WHMCS\Module\Server\Koality\Translator;
use WHMCS\Module\Server\Koality\UrlHelper;

function koality_getKaApiClient(array $params): KaApi
{
    return new KaApi(
        $params[ServerOption::SERVER_SCHEME],
        $params[ServerOption::SERVER_HOST],
        (int) $params[ServerOption::SERVER_PORT],
        $params[ServerOption::SERVER_USERNAME],
        $params[ServerOption::SERVER_PASSWORD]
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
        ProductOption::PLAN_ID => [
            'FriendlyName' => $translator->translate('koality_label_plan'),
            'Type' => 'dropdown',
            'Options' => $planOptions,
            'Default' => $proPlan->getId(),
            'SimpleMode' => true,
        ],
        ProductOption::DOMAIN_APPLICATION => [
            'FriendlyName' => $translator->translate('koality_label_domain_application'),
            'Type' => 'text',
            'Default' => '',
            'Description' => 'optional',
            'SimpleMode' => true,
        ],
        ProductOption::DOMAIN_LICENSE_ACTIVATION => [
            'FriendlyName' => $translator->translate('koality_label_domain_license_activation'),
            'Type' => 'text',
            'Default' => '',
            'Description' => 'optional',
            'SimpleMode' => true,
        ],
        ProductOption::ADDITIONAL_SINGLE_PROJECT => [
            'FriendlyName' => $translator->translate('koality_label_additional_single_projects'),
            'Type' => 'text',
            'Default' => '',
            'Description' => 'optional',
            'SimpleMode' => true,
        ],
        ProductOption::ADDITIONAL_THIRTY_PROJECTS => [
            'FriendlyName' => $translator->translate('koality_label_additional_thirty_projects'),
            'Type' => 'text',
            'Default' => '',
            'Description' => 'optional',
            'SimpleMode' => true,
        ],
    ];
}

function koality_ClientArea(array $params): string
{
    global $CONFIG;

    $kaApi = koality_getKaApiClient($params);
    $keyId = $params['model']->serviceProperties->get(ServiceProperty::KEY_ID);
    $translator = Translator::getInstance($CONFIG);

    try {
        $license = $kaApi->retrieveLicense($keyId);
        $domainApplication = $params[ProductOption::DOMAIN_APPLICATION];
        $domainLicenseActivation = $params[ProductOption::DOMAIN_LICENSE_ACTIVATION];
        $dashboardUrl = UrlHelper::getDashboardUrl($domainApplication);
        $activationUrl = UrlHelper::getActivationUrl($license, $domainLicenseActivation);

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
        $quantityOfSingleAdditionalProjects = ProductOption::additionalSingleProjectAllowance($params);
        $quantityOfThirtyAdditionalProjects = ProductOption::additionalThirtyProjectsAllowance($params);
        $plans = new PlanCollection();
        $plan = $plans->getPlanById($params[ProductOption::PLAN_ID]);
        $kaApi = koality_getKaApiClient($params);
        $license = $kaApi->createLicense($plan, $quantityOfSingleAdditionalProjects, $quantityOfThirtyAdditionalProjects);
        $domainLicenseActivation = $params[ProductOption::DOMAIN_LICENSE_ACTIVATION];

        $params['model']->serviceProperties->save([
            ServiceProperty::KEY_ID => $license->getKeyIdentifiers()->getKeyId(),
            ServiceProperty::ACTIVATION_CODE => $license->getKeyIdentifiers()->getActivationCode(),
            ServiceProperty::ACTIVATION_URL => UrlHelper::getActivationUrl($license, $domainLicenseActivation),
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
        $keyId = $params['model']->serviceProperties->get(ServiceProperty::KEY_ID);
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
        $keyId = $params['model']->serviceProperties->get(ServiceProperty::KEY_ID);
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
        $keyId = $params['model']->serviceProperties->get(ServiceProperty::KEY_ID);
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
        $keyId = $params['model']->serviceProperties->get(ServiceProperty::KEY_ID);
        $quantityOfSingleAdditionalProjects = ProductOption::additionalSingleProjectAllowance($params);
        $quantityOfThirtyAdditionalProjects = ProductOption::additionalThirtyProjectsAllowance($params);
        $plans = new PlanCollection();
        $plan = $plans->getPlanById($params[ProductOption::PLAN_ID]);
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
