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
 * Class AdminSqualomailModuleCustomersController
 */
class AdminSqualomailModuleCustomersController extends \PrestaChamps\SqualomailModule\Controllers\BaseSQMObjectController
{
    public $entityPlural   = 'customers';
    public $entitySingular = 'customer';

    public function initContent()
    {
        $this->addCSS($this->module->getLocalPath() . 'views/css/main.css');
        if (\Shop::getContext() !== \Shop::CONTEXT_SHOP) {
            $this->content = '';
            $this->warnings[] = $this->module->l('Please select a shop');
        } else {
            parent::initContent();
        }
    }
}
