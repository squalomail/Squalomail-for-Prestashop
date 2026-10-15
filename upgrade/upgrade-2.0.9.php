<?php
/**
 * SqualoMail
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Commercial License
 * you can't distribute, modify or sell this code
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file
 * If you need help please contact leo@prestachamps.com
 *
 * @author    Squalomail
 * @copyright Squalomail
 * @license   commercial
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Drop the per-request script hook and cache the connected site script.
 * Verification failure must not abort the upgrade; the next enable or store
 * sync retries it.
 *
 * @param Squalomailmodule $object
 *
 * @return bool
 */
function upgrade_module_2_0_9(Squalomailmodule $object)
{
    $object->unregisterHook('actionFrontControllerSetMedia');
    $object->verifySiteScript();

    return true;
}
