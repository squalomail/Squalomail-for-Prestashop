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
class AdminSqualomailModuleListsController extends \PrestaChamps\SqualomailModule\Controllers\BaseSQMObjectController
{
    public $entityPlural   = 'lists';
    public $entitySingular = 'list';

    /**
     * @throws Exception
     */
    public function processNew()
    {
        $list_name = \Tools::getValue('list_name');
        if ($list_name) {
            $this->action = null;
            if ($this->createSqualomailList($list_name)) {
                $this->confirmations[] = $this->l('List created successfully');
            } else {
                $this->errors[] = $this->l("Oups! Failed to create list: {$this->squalomail->getLastError()}");
            }
        }
    }

    /**
     * @param $list_name
     *
     * @return array|false
     * @throws Exception
     */
    private function createSqualomailList($list_name)
    {
        return \PrestaChamps\SqualomailModule\Factories\ListFactory::make(
            $list_name,
            $this->module->getApiClient(),
            $this->context
        );
    }

    protected function getListApiEndpointUrl()
    {
        return '/lists';
    }

    protected function getSingleApiEndpointUrl($entityId)
    {
        return "lists/{$entityId}";
    }
}
