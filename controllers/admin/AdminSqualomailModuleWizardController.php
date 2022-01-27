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
 * Class AdminSqualomailModuleWizardController
 *
 * @property Squalomailmodule $module
 */
class AdminSqualomailModuleWizardController extends ModuleAdminController
{
    public $bootstrap = true;

    /**
     * @throws PrestaShopException
     * @throws SmartyException
     */
    public function initContent()
    {
        $this->addCSS($this->module->getLocalPath() . 'views/css/main.css');
        if (\Shop::getContext() !== \Shop::CONTEXT_SHOP) {
            $this->content = '';
            $this->warnings[] = $this->module->l('Please select a shop');
        } else {
            Media::addJsDef(array('wizardUrl' => $this->context->link->getAdminLink($this->controller_name)));
            $this->addCSS($this->module->getLocalPath() . 'views/css/smart_wizard.css');
            $this->addCSS($this->module->getLocalPath() . 'views/css/smart_wizard_theme_dots.css');
            $this->addCSS($this->module->getLocalPath() . 'views/css/toastr.css');
            $this->addCSS($this->module->getLocalPath() . 'views/css/spinner.css');
            $this->addJS($this->module->getLocalPath() . 'views/js/jquery.smartWizard.js');
            $this->addJS($this->module->getLocalPath() . 'views/js/setup-wizard.js');
            $this->addJS($this->module->getLocalPath() . 'views/js/toastr.min.js');
            $this->addJS($this->module->getLocalPath() . 'views/js/ajaxq.js');
            $this->addJS($this->module->getLocalPath() . 'views/js/array.chunk.js');


            Media::addJsDef(array(
                'statePending' => SqualomailModuleConfig::STATUSES_FOR_PENDING,
                'stateRefunded' => SqualomailModuleConfig::STATUSES_FOR_REFUNDED,
                'stateCancelled' => SqualomailModuleConfig::STATUSES_FOR_CANCELLED,
                'stateShipped' => SqualomailModuleConfig::STATUSES_FOR_SHIPPED,
                'statePaid' => SqualomailModuleConfig::STATUSES_FOR_PAID,
                'productIds' => array_column(
                    Product::getSimpleProducts(\Context::getContext()->language->id),
                    'id_product'
                ),
                'promoCodeIds' => $this->getCartRules(),
                'orderIds' => $this->getOrderIds(),
                'categoryIds' => $this->getCategoryIds(),
                'customerIds' => array_column(Customer::getCustomers(true), 'id_customer'),
                'syncUrl' => $this->context->link->getAdminLink($this->controller_name),
                'itemsPerRequest' => 50,
            ));
            $this->context->smarty->assign(array(
                'apiKey' => Configuration::get(SqualomailModuleConfig::SQUALOMAIL_API_KEY),
                'sqmEmail' => $this->getSqualomailUserEmail(Configuration::get(SqualomailModuleConfig::SQUALOMAIL_API_KEY)),
            ));
            $this->content .= $this->context->smarty->fetch(
                $this->module->getLocalPath() . 'views/templates/admin/wizard.tpl'
            );
            if (Shop::getContext() !== Shop::CONTEXT_SHOP) {
                $this->content = '';
                $this->context->controller->warnings[] = $this->module->l('Please select a shop');
            }

            if (!Tools::usingSecureMode()) {
                $this->content = '';
                $this->context->controller->warnings[] = $this->module->l('Please use HTTPS for authenticating to Squalomail');
            }
            parent::initContent();
        }
    }

    protected function getSqualomailUserEmail($apiKey)
    {
        try {
            $sqm = new \DrewM\SqualoMail\SqualoMail($apiKey);
            $response = $sqm->get('/');
        } catch (Exception $exception) {
            return null;
        }

        return (isset($response['email'])) ? $response['email'] : null;
    }

    protected function getCartRules()
    {
        $query = new DbQuery();
        $query->from('cart_rule');
        $query->select('id_cart_rule');
        $query->where('shop_restriction = 0');
        $ids = array_column(Db::getInstance()->executeS($query), 'id_cart_rule');

        $query = new DbQuery();
        $query->from('cart_rule_shop');
        $query->select('id_cart_rule');
        $query->where('id_shop = ' . pSQL($this->context->shop->id));
        $result = array_column(Db::getInstance()->executeS($query), 'id_cart_rule');
        $result = array_unique(array_merge($ids, $result));
        sort($result, SORT_NUMERIC);

        return $result;
    }

    protected function getOrderIds()
    {
        $shopId = Shop::getContextShopID();
        $query = new DbQuery();
        $query->from('orders');
        $query->select('id_order');
        if ($shopId) {
            $query->where("id_shop = {$shopId}");
        }

        return array_column(Db::getInstance()->executeS($query), 'id_order');
    }

    protected function getCategoryIds()
    {
        $categories = Category::getCategories($this->context->language->id, false, false);
        $categoryIds = array();
        foreach($categories as $category) {
            array_push($categoryIds, $category["id_category"]);
        }

        return $categoryIds;
    }

    public function processApiKeyCheck()
    {
        $apiKey = Tools::getValue('apiKey');
        $email = $this->getSqualomailUserEmail($apiKey);
        
        if (isset($email)) {
            $this->ajaxDie(
                array(
                    'hasError' => false, 
                    'error' => null,
                    'email' => $email
                )
            );
        } else {
            $this->ajaxDie(
                array(
                    'hasError' => true,
                    'error' => $this->module->l('Invlid api key'),
                ),
                null,
                null,
                400
            );
        }
    }

    public function processApiKey()
    {
        try {
            $apiKey = Tools::getValue('apiKey');
            $sqm = new \DrewM\SqualoMail\SqualoMail($apiKey);
            $sqm->get('ping');
            if ($sqm->success()) {
                Configuration::updateValue(SqualomailModuleConfig::SQUALOMAIL_API_KEY, $apiKey);
                $this->ajaxDie(array('hasError' => false, 'error' => null));
            } else {
                $this->ajaxDie(
                    array(
                        'hasError' => true,
                        'error' => $this->module->l('Invlid api key'),
                    ),
                    null,
                    null,
                    400
                );
            }
        } catch (Exception $exception) {
            $this->ajaxDie(
                array(
                    'hasError' => true,
                    'error' => $sqm->getLastResponse(),
                ),
                null,
                null,
                400
            );
        }
    }

    public function getStateMapping()
    {
        try {
            $configValues = SqualomailModuleConfig::getConfigurationValues();
            $this->ajaxDie(
                array(
                    'hasError' => false,
                    'mapping' => array(
                        SqualomailModuleConfig::STATUSES_FOR_PENDING =>
                            $configValues[SqualomailModuleConfig::STATUSES_FOR_PENDING],
                        SqualomailModuleConfig::STATUSES_FOR_PENDING =>
                            $configValues[SqualomailModuleConfig::STATUSES_FOR_REFUNDED],
                        SqualomailModuleConfig::STATUSES_FOR_CANCELLED =>
                            $configValues[SqualomailModuleConfig::STATUSES_FOR_CANCELLED],
                        SqualomailModuleConfig::STATUSES_FOR_SHIPPED =>
                            $configValues[SqualomailModuleConfig::STATUSES_FOR_SHIPPED],
                        SqualomailModuleConfig::STATUSES_FOR_PAID =>
                            $configValues[SqualomailModuleConfig::STATUSES_FOR_PAID],
                    ),
                ),
                null,
                null,
                400
            );
        } catch (Exception $exception) {
            $this->ajaxDie(
                array(
                    'hasError' => true,
                    'error' => $exception->getMessage(),
                ),
                null,
                null,
                400
            );
        }
    }

    public function processStateMapping()
    {
        try {
            $statuses = Tools::getValue('states');
            if (isset($statuses[SqualomailModuleConfig::STATUSES_FOR_PENDING]) &&
                isset($statuses[SqualomailModuleConfig::STATUSES_FOR_REFUNDED]) &&
                isset($statuses[SqualomailModuleConfig::STATUSES_FOR_CANCELLED]) &&
                isset($statuses[SqualomailModuleConfig::STATUSES_FOR_SHIPPED]) &&
                isset($statuses[SqualomailModuleConfig::STATUSES_FOR_PAID]) &&
                is_array($statuses[SqualomailModuleConfig::STATUSES_FOR_PENDING]) &&
                is_array($statuses[SqualomailModuleConfig::STATUSES_FOR_REFUNDED]) &&
                is_array($statuses[SqualomailModuleConfig::STATUSES_FOR_CANCELLED]) &&
                is_array($statuses[SqualomailModuleConfig::STATUSES_FOR_SHIPPED]) &&
                is_array($statuses[SqualomailModuleConfig::STATUSES_FOR_PAID])
            ) {
                SqualomailModuleConfig::saveValue(
                    SqualomailModuleConfig::STATUSES_FOR_PENDING,
                    json_encode($statuses[SqualomailModuleConfig::STATUSES_FOR_PENDING])
                );
                SqualomailModuleConfig::saveValue(
                    SqualomailModuleConfig::STATUSES_FOR_REFUNDED,
                    json_encode($statuses[SqualomailModuleConfig::STATUSES_FOR_REFUNDED])
                );
                SqualomailModuleConfig::saveValue(
                    SqualomailModuleConfig::STATUSES_FOR_CANCELLED,
                    json_encode($statuses[SqualomailModuleConfig::STATUSES_FOR_CANCELLED])
                );
                SqualomailModuleConfig::saveValue(
                    SqualomailModuleConfig::STATUSES_FOR_SHIPPED,
                    json_encode($statuses[SqualomailModuleConfig::STATUSES_FOR_SHIPPED])
                );
                SqualomailModuleConfig::saveValue(
                    SqualomailModuleConfig::STATUSES_FOR_PAID,
                    json_encode($statuses[SqualomailModuleConfig::STATUSES_FOR_PAID])
                );
                $this->ajaxDie(array('hasError' => false, 'error' => null));
            }
            throw new Exception('Invalid data');
        } catch (Exception $exception) {
            $this->ajaxDie(
                array(
                    'hasError' => true,
                    'error' => $exception->getMessage(),
                ),
                null,
                null,
                400
            );
        }
    }

    public function processGetStates()
    {
        try {
            $configValues = SqualomailModuleConfig::getConfigurationValues();

            $orderStates = OrderState::getOrderStates($this->context->language->id);
            $this->ajaxDie(array(
                'hasError' => false,
                'error' => null,
                'states' => $orderStates,
                'mapping' => array(
                    SqualomailModuleConfig::STATUSES_FOR_PENDING =>
                        $configValues[SqualomailModuleConfig::STATUSES_FOR_PENDING],
                    SqualomailModuleConfig::STATUSES_FOR_REFUNDED =>
                        $configValues[SqualomailModuleConfig::STATUSES_FOR_REFUNDED],
                    SqualomailModuleConfig::STATUSES_FOR_CANCELLED =>
                        $configValues[SqualomailModuleConfig::STATUSES_FOR_CANCELLED],
                    SqualomailModuleConfig::STATUSES_FOR_SHIPPED =>
                        $configValues[SqualomailModuleConfig::STATUSES_FOR_SHIPPED],
                    SqualomailModuleConfig::STATUSES_FOR_PAID =>
                        $configValues[SqualomailModuleConfig::STATUSES_FOR_PAID],
                ),
            ));
        } catch (Exception $exception) {
            $this->ajaxDie(
                array(
                    'hasError' => true,
                    'error' => $exception->getMessage(),
                ),
                null,
                null,
                400
            );
        }
    }

    public function processSyncStores()
    {
        try {
//            $shops = array_column(Shop::getShops(true), 'id_shop');
            $command = new \PrestaChamps\SqualomailModule\Commands\StoreSyncCommand(
                $this->context,
                $this->module->getApiClient(),
                array($this->context->shop->id)
            );
            $command->setSyncMode($command::SYNC_MODE_REGULAR);
            $command->setMethod($command::SYNC_METHOD_POST);
            $command->execute();
            $command->setMethod($command::SYNC_METHOD_PATCH);
            $this->ajaxDie(array(
                'hasError' => false,
                'error' => null,
                'result' => $command->execute(),
            ));
        } catch (Exception $exception) {
            $this->ajaxDie(
                array(
                    'hasError' => true,
                    'error' => $exception->getMessage(),
                ),
                null,
                null,
                400
            );
        }
    }

    public function processSyncCustomers()
    {
        try {
            $results = array();
            $customerIds = Tools::getValue('items');
            $command = new \PrestaChamps\SqualomailModule\Commands\CustomerSyncCommand(
                Context::getContext(),
                $this->module->getApiClient(),
                $customerIds
            );
            $command->setSyncMode($command::SYNC_MODE_REGULAR);
            $command->setMethod($command::SYNC_METHOD_PUT);
            $results[] = $command->execute();
            $this->ajaxDie(array(
                'hasError' => false,
                'error' => null,
                'result' => $results,
            ));
        } catch (Exception $exception) {
            $this->ajaxDie(
                array(
                    'hasError' => true,
                    'error' => $exception->getMessage(),
                ),
                null,
                null,
                400
            );
        }
    }

    public function processSyncPromoCodes()
    {
        try {
            $results = array();
            $objectIds = Tools::getValue('items');
            $objects = array();

            foreach ($objectIds as $objectId) {
                $object = new CartRule($objectId, $this->context->language->id, $this->context->shop->id);
                if (Validate::isLoadedObject($object)) {
                    $objects[] = $object;
                }
            }
            $command = new \PrestaChamps\SqualomailModule\Commands\CartRuleSyncCommand(
                Context::getContext(),
                $this->module->getApiClient(),
                $objects
            );
            $command->setSyncMode($command::SYNC_MODE_REGULAR);
            $command->setMethod($command::SYNC_METHOD_POST);
            $results[] = $command->execute();
            $command->setMethod($command::SYNC_METHOD_PATCH);
            $results[] = $command->execute();
            $this->ajaxDie(array(
                'hasError' => false,
                'error' => null,
                'result' => $results,
            ));
        } catch (Exception $exception) {
            $this->ajaxDie(
                array(
                    'hasError' => true,
                    'error' => $exception->getMessage(),
                ),
                null,
                null,
                400
            );
        }
    }

    public function processSyncProducts()
    {
        try {
            $results = array();
            /*
            $productIds = array_column(
                Product::getSimpleProducts(\Context::getContext()->language->id),
                'id_product'
            );*/
            $productIds = Tools::getValue('items');
            $command = new \PrestaChamps\SqualomailModule\Commands\ProductSyncCommand(
                $this->context,
                $this->module->getApiClient(),
                $productIds
            );
            $command->setSyncMode($command::SYNC_MODE_REGULAR);
            $command->setMethod($command::SYNC_METHOD_POST);
            $results[] = $command->execute();
            $command->setMethod($command::SYNC_METHOD_PATCH);
            $results[] = $command->execute();
            $this->ajaxDie(array(
                'hasError' => false,
                'error' => null,
                'result' => $results,
            ));
        } catch (Exception $exception) {
            $this->ajaxDie(
                array(
                    'hasError' => true,
                    'error' => $exception->getMessage(),
                ),
                null,
                null,
                400
            );
        }
    }

    public function processSyncCategories(){
        try {
            $results = array();
            $categoryIds = Tools::getValue('items');
            $command = new \PrestaChamps\SqualomailModule\Commands\CategoriesSyncCommand(
                $this->context,
                $this->module->getApiClient(),
                $categoryIds
            );
            $command->setSyncMode($command::SYNC_MODE_REGULAR);
            $command->setMethod($command::SYNC_METHOD_POST);
            $results[] = $command->execute();
            $command->setMethod($command::SYNC_METHOD_PUT);
            $results[] = $command->execute();
            $this->ajaxDie(array(
                'hasError' => false,
                'error' => null,
                'result' => $results,
            ));
        } catch (Exception $exception) {
            $this->ajaxDie(
                array(
                    'hasError' => true,
                    'error' => $exception->getMessage(),
                ),
                null,
                null,
                400
            );
        }
    }

    public function processSyncOrders()
    {
        try {
            $results = array();
            $orderIds = Tools::getValue('items');
            $command = new \PrestaChamps\SqualomailModule\Commands\OrderSyncCommand(
                $this->context,
                $this->module->getApiClient(),
                $orderIds
            );
            $command->setSyncMode($command::SYNC_MODE_REGULAR);
            $command->setMethod($command::SYNC_METHOD_POST);
            $results[] = $command->execute();
            $command->setMethod($command::SYNC_METHOD_PATCH);
            $results[] = $command->execute();
            $this->ajaxDie(array(
                'hasError' => false,
                'error' => null,
                'result' => $results,
            ));
        } catch (Exception $exception) {
            $this->ajaxDie(
                array(
                    'hasError' => true,
                    'error' => $exception->getMessage(),
                ),
                null,
                null,
                400
            );
        }
    }

    public function processListSelect()
    {
        try {
            $listId = Tools::getValue('listId');
            Configuration::updateValue(SqualomailModuleConfig::SQUALOMAIL_LIST_ID, $listId);
            $this->ajaxDie(array('hasError' => false, 'error' => null));
        } catch (Exception $exception) {
            $this->ajaxDie(
                array(
                    'hasError' => true,
                    'error' => $exception->getMessage(),
                ),
                null,
                null,
                400
            );
        }
    }

    public function processGetLists()
    {
        try {
            $lists = $this->module->getApiClient()->get(
                'lists',
                array('fields' => 'lists.name,lists.id', 'count' => 999)
            );
            if (!$lists || empty($lists)) {
                \PrestaChamps\SqualomailModule\Factories\ListFactory::make(
                    $this->context->shop->name,
                    $this->module->getApiClient(),
                    $this->context
                );
                $lists = $this->module->getApiClient()->get(
                    'lists',
                    array('fields' => 'lists.name,lists.id', 'count' => 999)
                );
            }
            $this->ajaxDie(
                array(
                    'hasError' => false,
                    'error' => null,
                    'lists' => $lists['lists'],
                    'selectedList' => Configuration::get(SqualomailModuleConfig::SQUALOMAIL_LIST_ID),
                )
            );
        } catch (Exception $exception) {
            $this->ajaxDie(
                array(
                    'hasError' => true,
                    'error' => $exception->getMessage(),
                ),
                null,
                null,
                400
            );
        }
    }

    public function processBatchInfo()
    {
        try {
            $batchId = Tools::getValue('id', false);
            if (!$batchId) {
                throw new Exception('Invalid BatchId');
            }
            $sqm = $this->module->getApiClient();

            $this->ajaxDie(array('hasErrors' => false, 'batch' => $sqm->new_batch($batchId)->check_status($batchId)));
        } catch (Exception $exception) {
            $this->ajaxDie(
                array(
                    'hasError' => true,
                    'error' => $exception->getMessage(),
                ),
                null,
                null,
                400
            );
        }
    }

    /**
     * @param null $value
     * @param null $controller
     * @param null $method
     * @param int  $statusCode
     */
    public function ajaxDie($value = null, $controller = null, $method = null, $statusCode = 200)
    {
        header('Content-Type: application/json');
        if (!is_scalar($value)) {
            $value = json_encode($value);
        }

        http_response_code($statusCode);
        parent::ajaxDie($value, $controller, $method);
    }
}
