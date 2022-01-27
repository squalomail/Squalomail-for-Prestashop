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
 * Class AdminSqualomailModuleListsController
 *
 * @property Squalomailmodule $module
 */
class AdminSqualomailModuleSitesController extends \PrestaChamps\SqualomailModule\Controllers\BaseSQMObjectController
{
    public $entityPlural   = 'sites';
    public $entitySingular = 'connected-sites';


    protected function getListApiEndpointUrl()
    {
        return '/connected-sites';
    }

    protected function getSingleApiEndpointUrl($entityId)
    {
        return "connected-sites/{$entityId}";
    }

    protected function deleteEntity($id)
    {
        $this->squalomail->delete("/connected-sites/{$id}");

        if ($this->squalomail->success()) {
            return true;
        }

        return false;
    }
}
