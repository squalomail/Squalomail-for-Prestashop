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
 * Class AdminSqualomailModuleCartsController
 *
 * @property Squalomailmodule $module
 */
class AdminSqualomailModuleStoresController extends \PrestaChamps\SqualomailModule\Controllers\BaseSQMObjectController
{
    public $entityPlural   = 'stores';
    public $entitySingular = 'store';

    protected function getListApiEndpointUrl()
    {
        return '/ecommerce/stores';
    }

    protected function deleteEntity($id)
    {
        $this->squalomail->delete("/ecommerce/stores/{$id}");

        if ($this->squalomail->success()) {
            return true;
        }
        return false;
    }
}
