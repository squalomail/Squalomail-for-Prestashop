<?php
/**
 * PrestaChamps
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
 * @copyright PrestaChamps
 * @license   commercial
 */

/**
 * Class AdminSqualomailModuleBatchesController
 */
class AdminSqualomailModuleAutomationsController extends \PrestaChamps\SqualomailModule\Controllers\BaseSQMObjectController
{
    public $entityPlural   = 'automations';
    public $entitySingular = 'workflow';

    protected function getListApiEndpointUrl()
    {
        return '/automations';
    }

    protected function getSingleApiEndpointUrl($entityId)
    {
        return "automations/{$entityId}";
    }

    protected function deleteEntity($id)
    {
        $this->squalomail->delete($this->getSingleApiEndpointUrl($id));

        if ($this->squalomail->success()) {
            return true;
        }

        return false;
    }
}
