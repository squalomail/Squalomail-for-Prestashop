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

function upgrade_module_2_0_3(Squalomailmodule $object)
{
    return (
        $object->registerHook('displayFooterBefore') &&
        $object->registerHook('actionCustomerAccountAdd') &&
        $object->registerHook('actionCustomerAccountUpdate')

    );
}
