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

namespace PrestaChamps\SqualomailModule\Controllers;

use JasonGrimes\Paginator;
use \DrewM\SqualoMail\SqualoMail;
use PrestaChamps\PrestaShop\Helpers\LinkHelper;

/**
 * Class BaseSQMObjectController
 *
 * @package PrestaChamps\SqualomailModule\Exceptions
 */
abstract class BaseSQMObjectController extends \ModuleAdminController
{
    public $bootstrap = true;
    public $squalomail;

    public $entitySingular;
    public $entityPlural;

    protected $entitiesPerPage = 20;
    protected $offset          = 0;
    protected $currentPage     = 1;
    protected $totalPageNumber = 1;
    protected $totalEntities   = 0;
    protected $entity;

    /**
     * @var int Squalomail store ID
     */
    public $sqmStoreId = 1;

    /**
     * AdminSqualomailModuleProductsController constructor.
     *
     * @throws \PrestaShopException
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->sqmStoreId = $this->context->shop->id;
        try {
            $this->squalomail = new SqualoMail(\Configuration::get(\SqualomailModuleConfig::SQUALOMAIL_API_KEY));
        } catch (\Exception $exception) {
            $this->errors[] = $exception->getMessage();
        }
    }

    protected function getListApiEndpointUrl()
    {
        return "ecommerce/stores/{$this->sqmStoreId}/{$this->entityPlural}";
    }

    protected function getSingleApiEndpointUrl($entityId)
    {
        return "ecommerce/stores/{$this->sqmStoreId}/{$this->entityPlural}/{$entityId}";
    }

    /**
     * @return mixed
     * @throws \PrestaChamps\SqualomailModule\Exceptions\SqualoMailException
     * @throws \Exception
     */
    protected function getEntities()
    {
        if (!$this->squalomail) {
            return array();
        }
        $result = $this->squalomail->get(
            $this->getListApiEndpointUrl(),
            array('count' => $this->entitiesPerPage, 'offset' => ($this->currentPage - 1) * $this->entitiesPerPage),
            999
        );
        if ($this->squalomail->success()) {
            $this->totalEntities = $result['total_items'];

            $this->totalPageNumber = ceil($this->totalEntities / $this->entitiesPerPage);
            return $result[$this->entityPlural];
        }

        throw new \PrestaChamps\SqualomailModule\Exceptions\SqualoMailException($this->squalomail->getLastError());
    }

    /**
     * @throws \SmartyException
     */
    public function initContent()
    {
        $this->addCSS($this->module->getLocalPath() . 'views/css/main.css');
        $this->content .= $this->context->smarty->fetch(
            $this->module->getLocalPath() . 'views/templates/admin/config/navbar.tpl'
        );
        try {
            if (empty($this->action) || $this->action === 'page') {
                $this->renderEntityList();
            }
            parent::initContent();
        } catch (\Exception $exception) {
            $this->errors[] = $exception->getMessage();
        }
        parent::initContent();
    }

    /**
     * @throws \PrestaChamps\SqualomailModule\Exceptions\SqualoMailException
     * @throws \SmartyException
     */
    protected function renderEntityList()
    {
        $this->context->smarty->assign(array($this->entityPlural => $this->getEntities()));
        $this->renderPagination();
        $this->content .= $this->context->smarty->fetch(
            $this->module->getLocalPath() . "views/templates/admin/entity_list/{$this->entityPlural}.tpl"
        );
    }

    /**
     * Generate the link template for the pagination buttons
     *
     * @return string
     * @throws \PrestaShopException
     */
    protected function getPaginationPageLinkTemplate()
    {
        return urldecode(
            LinkHelper::getAdminLink(
                $this->controller_name,
                true,
                array(),
                array(
                    'action' => 'page',
                    'page' => '(:num)',
                )
            )
        );
    }

    /**
     * @throws \PrestaShopException
     */
    protected function renderPagination()
    {
        $pagination = new Paginator(
            $this->totalEntities,
            $this->entitiesPerPage,
            $this->currentPage,
            $this->getPaginationPageLinkTemplate()
        );

        $this->content .= "<div class='text-center'>{$pagination}</div>";
    }

    protected function deleteEntity($id)
    {
        $this->squalomail->delete("/ecommerce/stores/{$this->sqmStoreId}/{$this->entityPlural}/{$id}");

        if ($this->squalomail->success()) {
            return true;
        }

        return false;
    }

    public function processEntityDelete()
    {
        $entityId = \Tools::getValue('entity_id', false);

        if ($entityId) {
            if ($this->deleteEntity($entityId)) {
                $this->confirmations[] = $this->module->l('Entity deleted');
            } else {
                $this->errors[] = $this->module->l('Could not delete the entity');
            }
        }
    }

    public function processPage()
    {
        $page = \Tools::getValue('page', false);
        if ($page && is_numeric($page) && $page > 0) {
            $this->currentPage = (int)$page;
        }
    }

    /**
     * @throws \SmartyException
     */
    public function processSingle()
    {
        $entityId = \Tools::getValue('entity_id');
        if (!$entityId) {
            $this->errors[] = $this->module->l('Invalid entity id');
            return;
        }
        $this->entity = $this->squalomail->get($this->getSingleApiEndpointUrl($entityId));
        $this->context->smarty->assign(array('entity' => $this->entity));
        $this->content .= $this->context->smarty->fetch(
            $this->module->getLocalPath() . "views/templates/admin/entity_single/{$this->entitySingular}.tpl"
        );
    }
}
