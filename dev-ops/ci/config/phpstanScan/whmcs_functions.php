<?php

declare(strict_types=1);

// This file is only for PHPStan to run correctly without excuses

/**
 * Register hook function call.
 *
 * @param string $hookPoint the hook point to call
 * @param int    $priority  the priority for the hook function
 * @param string|callable the function name to call or the anonymous function
 *
 * @return mixed this depends on the hook function point
 */
function add_hook(string $hookPoint, int $priority, $callable)
{
    throw new LogicException('This method is only for PHPStan to run correctly without excuses');
}

/**
 * Log activity.
 *
 * @param string $message The message to log
 * @param int    $userId  An optional user id to which the log entry relates
 */
function logActivity($message, $userId): void
{
    throw new LogicException('This method is only for PHPStan to run correctly without excuses');
}

/**
 * Log module call.
 *
 * @param string              $module        The name of the module
 * @param string              $action        The name of the action being performed
 * @param string|array<mixed> $requestString The input parameters for the API call
 * @param string|array<mixed> $responseData  The response data from the API call
 * @param string|array<mixed> $processedData The resulting data after any post processing (eg. json decode, xml decode, etc...)
 * @param array<mixed>        $replaceVars   An array of strings for replacement
 */
function logModuleCall($module, $action, $requestString, $responseData, $processedData = '', $replaceVars = []): void
{
    throw new LogicException('This method is only for PHPStan to run correctly without excuses');
}
