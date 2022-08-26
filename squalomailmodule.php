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

use PrestaChamps\SqualomailModule\Formatters\ListMemberFormatter;

/**
 * Class Squalomailmodule
 */
class Squalomailmodule extends Module
{
    /**
     * @var \DrewM\SqualoMail\SqualoMail SqualoMail API client object
     *
     * @see https://github.com/drewm/squalomail-api
     */
    protected $apiClient;

    public $menus = array(
        array(
            'is_root'           => true,
            'name'              => 'Squalomail Config',
            'class_name'        => 'squalomailmodule',
            'visible'           => true,
            'parent_class_name' => 0,
        ),
        array(
            'is_root'           => false,
            'name'              => 'Squalomail Config',
            'class_name'        => 'AdminSqualomailModuleConfig',
            'visible'           => true,
            'parent_class_name' => 'squalomailmodule',
        ),
        array(
            'is_root'           => false,
            'name'              => 'Squalomail Setup Wizard',
            'class_name'        => 'AdminSqualomailModuleWizard',
            'visible'           => true,
            'parent_class_name' => 'squalomailmodule',
        ),
        array(
            'is_root'           => false,
            'name'              => 'Squalomail List',
            'class_name'        => 'AdminSqualomailModuleLists',
            'visible'           => false,
            'parent_class_name' => 'squalomailmodule',
        ),
        array(
            'is_root'           => false,
            'name'              => 'Squalomail Batches',
            'class_name'        => 'AdminSqualomailModuleBatches',
            'visible'           => false,
            'parent_class_name' => 'squalomailmodule',
        ),
        array(
            'is_root'           => false,
            'name'              => 'Squalomail Carts',
            'class_name'        => 'AdminSqualomailModuleCarts',
            'visible'           => false,
            'parent_class_name' => 'squalomailmodule',
        ),
        array(
            'is_root'           => false,
            'name'              => 'Squalomail Customers',
            'class_name'        => 'AdminSqualomailModuleCustomers',
            'visible'           => false,
            'parent_class_name' => 'squalomailmodule',
        ),
        array(
            'is_root'           => false,
            'name'              => 'Squalomail Orders',
            'class_name'        => 'AdminSqualomailModuleOrders',
            'visible'           => false,
            'parent_class_name' => 'squalomailmodule',
        ),
        array(
            'is_root'           => false,
            'name'              => 'Squalomail Products',
            'class_name'        => 'AdminSqualomailModuleProducts',
            'visible'           => false,
            'parent_class_name' => 'squalomailmodule',
        ),
        array(
            'is_root'           => false,
            'name'              => 'Squalomail Categories',
            'class_name'        => 'AdminSqualomailModuleCategories',
            'visible'           => false,
            'parent_class_name' => 'squalomailmodule',
        ),
        array(
            'is_root'           => false,
            'name'              => 'Squalomail Stores',
            'class_name'        => 'AdminSqualomailModuleStores',
            'visible'           => false,
            'parent_class_name' => 'squalomailmodule',
        ),
        array(
            'is_root'           => false,
            'name'              => 'Squalomail Sync',
            'class_name'        => 'AdminSqualomailModuleSync',
            'visible'           => false,
            'parent_class_name' => 'squalomailmodule',
        ),
        array(
            'is_root'           => false,
            'name'              => 'Squalomail Sites',
            'class_name'        => 'AdminSqualomailModuleSites',
            'visible'           => false,
            'parent_class_name' => 'squalomailmodule',
        ),
        array(
            'is_root'           => false,
            'name'              => 'Squalomail Automations',
            'class_name'        => 'AdminSqualomailModuleAutomations',
            'visible'           => false,
            'parent_class_name' => 'squalomailmodule',
        ),
        array(
            'is_root'           => false,
            'name'              => 'List members',
            'class_name'        => 'AdminSqualomailModuleListMembers',
            'visible'           => false,
            'parent_class_name' => 'squalomailmodule',
        ),
        array(
            'is_root'           => false,
            'name'              => 'Promo rules',
            'class_name'        => 'AdminSqualomailModulePromoRules',
            'visible'           => false,
            'parent_class_name' => 'squalomailmodule',
        ),
        array(
            'is_root'           => false,
            'name'              => 'Promo codes',
            'class_name'        => 'AdminSqualomailModulePromoCodes',
            'visible'           => false,
            'parent_class_name' => 'squalomailmodule',
        ),
    );


    public function __construct()
    {
        $this->name = 'squalomailmodule';
        $this->tab = 'administration';
        $this->version = '2.0.7';
        $this->author = 'Squalomail';
        $this->need_instance = 1;
        $this->bootstrap = true;
        $this->module_key = '793ebc5f330220c7fb7b817fe0d63a92';

        parent::__construct();

        $this->displayName = $this->l('Squalomail');
        $this->description = $this->l('Official Squalomail integration for PrestaShop');
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);

        require_once $this->getLocalPath() . 'vendor/autoload.php';
    }


    /**
     * Install the required tabs, configs and stuff
     *
     * @return bool
     * @throws PrestaShopException
     *
     * @throws PrestaShopDatabaseException
     * @since 0.0.1
     *
     */
    public function install()
    {
        $tabRepository = new \PrestaChamps\PrestaShop\Tab\TabRepository($this->menus, 'squalomailmodule');
        $tabRepository->install();

        return parent::install() &&
            // The moduleRoutes hook is necessary in order to load the autoloader
            $this->registerHook('moduleRoutes') &&
            $this->registerHook('displayFooter') &&
            $this->registerHook('displayHeader') &&
            $this->registerHook('actionCategoryAdd') &&
            $this->registerHook('actionCategoryUpdate') &&
            $this->registerHook('actionCategoryDelete') &&
            $this->registerHook('actionProductUpdate') &&
            $this->registerHook('actionValidateOrder') &&
            $this->registerHook('actionObjectUpdateAfter') &&
            $this->registerHook('actionObjectDeleteAfter') &&
            $this->registerHook('actionOrderStatusUpdate') &&
            $this->registerHook('actionCartSave') &&
            $this->registerHook('actionObjectCustomerAddAfter') &&
            $this->registerHook('actionObjectCartRuleAddAfter') &&
            $this->registerHook('actionObjectCartRuleDeleteBefore') &&
            $this->registerHook('displayAdminOrderContentOrder') &&
            $this->registerHook('displayAdminOrderTabOrder') &&
            $this->registerHook('displayBackOfficeTop') &&
            $this->registerHook('actionFrontControllerSetMedia') &&
            $this->registerHook('actionObjectCartRuleUpdateAfter') &&
            $this->registerHook('displayFooterBefore') &&
            $this->registerHook('actionCustomerAccountAdd') &&
            $this->registerHook('actionCustomerAccountUpdate');
    }

    public function runUpgradeModule()
    {
        return parent::runUpgradeModule(); // TODO: Change the autogenerated stub
    }

    /**
     * @return bool
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function uninstall()
    {
        $tabRepository = new \PrestaChamps\PrestaShop\Tab\TabRepository($this->menus, 'squalomailmodule');
        $tabRepository->uninstall();

        return parent::uninstall();
    }


    /**
     * Check if the current PrestaShop installation is version 1.7 or below
     *
     * @return bool
     */
    public static function isPs17()
    {
        return (bool)version_compare(_PS_VERSION_, '1.7', '>=');
    }


    /**
     * Redirect to the custom config controller
     *
     * @throws PrestaShopException
     */
    public function getContent()
    {
        Tools::redirectAdmin($this->context->link->getAdminLink('AdminSqualomailModuleConfig'));
    }

    /**
     * Place UTM tracking cookie when the user arrived via SqualoMail
     *
     * @param $params
     */
    public function hookDisplayHeader($params)
    {
        if ((Tools::getValue('utm_source') === 'squalomail' || !empty(Tools::getValue('sqm_cid')))
            && $this->isApiKeySet()) {
            $this->context->cookie->landing_site = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $sqm_cid = Tools::getValue('sqm_cid', false);
            $utm_source = Tools::getValue('utm_source', false);
            if ($sqm_cid) {
                setcookie('sqm_cid', Tools::getValue('sqm_cid'));
            }
            if ($utm_source) {
                setcookie('utm_source', urldecode(Tools::getValue('utm_source')));
            }
            $this->context->cookie->utm_source = Tools::getValue('utm_source');
            setcookie(
                'landing_site',
                (Tools::usingSecureMode() ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"
            );
        }
    }

    /**
     * Squalomail API client factory
     *
     * @throws Exception
     */
    public function getApiClient()
    {
        if ($this->apiClient instanceof \DrewM\SqualoMail\SqualoMail) {
            return $this->apiClient;
        }
        $this->apiClient = new DrewM\SqualoMail\SqualoMail(Configuration::get(SqualomailModuleConfig::SQUALOMAIL_API_KEY));

        return $this->apiClient;
    }

    /**
     * @param       $url
     * @param       $method
     * @param array $data
     *
     * @return mixed
     * @throws Exception
     */
    public function sendApiRequest($url, $method, $data = array())
    {
        if ($method === 'POST') {
            $this->getApiClient()->post($url, $data);
        } elseif ($method === 'PATCH') {
            $this->getApiClient()->patch($url, $data);
        } elseif ($method === 'PUT') {
            $this->getApiClient()->put($url, $data);
        } elseif ($method === 'DELETE') {
            $this->getApiClient()->delete($url, $data);
        } else {
            $this->getApiClient()->get($url, $data);
        }

        return $this->getApiClient()->getLastResponse();
    }

    /**
     * Display site SqualoMail site verification
     *
     * @param $params
     *
     * @return string
     */
    public function hookDisplayFooter($params)
    {
        if ($this->isApiKeySet()) {
            try {
                $result = $this->sendApiRequest("ecommerce/stores/{$this->context->shop->id}", 'GET');
                if ($this->getApiClient()->success()) {
                    $result = json_decode($result['body'], true);

                    if (isset($result['connected_site'])) {
                        $footer = $result['connected_site']['site_script']['fragment'];
                        if (!Configuration::get(SqualomailModuleConfig::SQUALOMAIL_SCRIPT_VERIFIED)) {
                            $site_id = $result['connected_site']['site_foreign_id'];
                            (new \PrestaChamps\SqualomailModule\Commands\SiteVerifyCommand($this->apiClient, $site_id))
                                ->execute();
                            $this->sendApiRequest(
                                "ecommerce/stores/{$this->context->shop->id}",
                                'POST',
                                array('is_syncing' => false)
                            );
                            Configuration::updateValue(SqualomailModuleConfig::SQUALOMAIL_SCRIPT_VERIFIED, true);
                        }

                        return $footer;
                    }
                }
                PrestaShopLogger::addLog("[SQUALOMAIL] :{$this->getApiClient()->getLastError()}");
            } catch (Exception $e) {
                PrestaShopLogger::addLog("[SQUALOMAIL] :{$e->getMessage()}");
            }
        }
        return '';
    }

    /**
     * @param $params
     *
     * @return string
     * @throws Exception
     */
    public function hookActionFrontControllerSetMedia($params)
    {
        if ($this->isApiKeySet()) {
            $result = $this->getApiClient()->get("ecommerce/stores/{$this->context->shop->id}");
            try {
                if (!Configuration::get(SqualomailModuleConfig::SQUALOMAIL_SCRIPT_VERIFIED)) {
                    $siteId = $result['connected_site']['site_foreign_id'];
                    $this->sendApiRequest(
                        "connected-sites/{$siteId}/actions/verify-script-installation",
                        'POST'
                    );
                    Configuration::updateValue(SqualomailModuleConfig::SQUALOMAIL_SCRIPT_VERIFIED, true);
                }
                $this->context->controller->addJS($result['connected_site']['site_script']['url'], false);
            } catch (Exception $exception) {
                PrestaShopLogger::addLog("[SQUALOMAIL] :{$exception->getMessage()}");
            }
        }
        return '';
    }

    /**
     * Sync the newly created customer to SqualoMail
     *
     * @param $params
     */
    public function hookActionObjectCustomerAddAfter($params)
    {
        if ($this->isApiKeySet()) {
            try {
                /**
                 * @var $customer Customer
                 */
                $customer = $params['object'];
                $command = new \PrestaChamps\SqualomailModule\Commands\CustomerSyncCommand(
                    $this->context,
                    $this->getApiClient(),
                    array($customer->id)
                );
                $command->setMethod(\PrestaChamps\SqualomailModule\Commands\CustomerSyncCommand::SYNC_METHOD_PUT);
                $command->triggerDoubleOptIn(true);
                $command->execute();
            } catch (Exception $exception) {
                $this->context->controller->errors[] = "[SQUALOMAIL] :{$exception->getMessage()}";
                PrestaShopLogger::addLog("[SQUALOMAIL] :{$exception->getMessage()}");
            }
        }
    }

    /**
     * @param $params
     *
     * @throws Exception
     * @todo Refactor code to use a service pattern
     *
     */
    public function hookActionObjectCartRuleAddAfter($params)
    {
        if ($this->isApiKeySet()) {
            $object = new CartRule($params['object']->id, $this->context->language->id);
            $command = new \PrestaChamps\SqualomailModule\Commands\CartRuleSyncCommand(
                $this->context,
                $this->getApiClient(),
                array($object)
            );
            $command->setMethod($command::SYNC_METHOD_POST);
            $command->setSyncMode($command::SYNC_MODE_REGULAR);
            $command->execute();
        }
    }

    /**
     * @param $params
     *
     * @throws Exception
     * @todo Refactor code to use a service pattern
     *
     */
    public function hookActionObjectCartRuleUpdateAfter($params)
    {
        if ($this->isApiKeySet()) {
            $object = new CartRule($params['object']->id, $this->context->language->id);
            $command = new \PrestaChamps\SqualomailModule\Commands\CartRuleSyncCommand(
                $this->context,
                $this->getApiClient(),
                array($object)
            );
            $command->setMethod($command::SYNC_METHOD_PATCH);
            $command->setSyncMode($command::SYNC_MODE_REGULAR);
            $command->execute();
        }
    }

    /**
     * @param $params
     *
     * @throws Exception
     * @todo Refactor code to use a service pattern
     *
     */
    public function hookActionObjectCartRuleDeleteBefore($params)
    {
        if ($this->isApiKeySet()) {
            $object = new CartRule($params['object']->id, $this->context->language->id);
            $command = new \PrestaChamps\SqualomailModule\Commands\CartRuleSyncCommand(
                $this->context,
                $this->getApiClient(),
                array($object)
            );
            $command->setMethod($command::SYNC_METHOD_DELETE);
            $command->setSyncMode($command::SYNC_MODE_REGULAR);
            $command->execute();
        }
    }


    /**
     * Create or update the cart in Squalomail
     *
     * @param $params
     *
     * @throws Exception
     * @todo Use command pattern instead
     *
     */
    public function hookActionCartSave($params)
    {
        if ($this->isApiKeySet() && (Tools::getValue('controller') !== 'adminaddresses')) {
            try {
                \PrestaChamps\SqualomailModule\Hooks\Action\CartSave::run(
                $this->context,
                $this->getApiClient()
            );
            } catch (Exception $exception) {
                $this->context->controller->errors[] = "[SQUALOMAIL] :{$exception->getMessage()}";
                PrestaShopLogger::addLog("[SQUALOMAIL] :{$exception->getMessage()}");
            }
        }
    }

    protected function getCartExists($cartId)
    {
        $this->getApiClient()->get(
            "/ecommerce/stores/{$this->context->shop->id}/carts/{$cartId}",
            array('fields' => array('id'))
        );

        if ($this->apiClient->success()) {
            return true;
        }

        return false;
    }

    /**
     * Sync the order status update to SqualoMail
     *
     * @param $params
     */
    public function hookActionOrderStatusUpdate($params)
    {
        if ($this->isApiKeySet()) {
            try {
                $orderId = null;
                if (isset($params['id_order'])) {
                    $orderId = $params['id_order'];
                }
                if (isset($params['newOrderStatus']) && isset($params['newOrderStatus'], $params['newOrderStatus']->id_order)) {
                    $orderId = $params['newOrderStatus']->id_order;
                }

                $order = new Order($orderId, $this->context->language->id);
                if (isset($params['newOrderStatus']) && isset($params['newOrderStatus'], $params['newOrderStatus']->id)) {
                    $order->current_state = $params['newOrderStatus']->id;
                }

                $shippingAddress = new \Address($order->id_address_delivery, $this->context->language->id);
                $billingAddress = new \Address($order->id_address_invoice, $this->context->language->id);
                $data = (new \PrestaChamps\SqualomailModule\Formatters\OrderFormatter(
                    $order,
                    $order->getCustomer(),
                    $billingAddress,
                    $shippingAddress,
                    $this->context
                ))->format();
                $result = $this->sendApiRequest(
                    "ecommerce/stores/{$this->context->shop->id}/orders",
                    'POST',
                    $data
                );
                if ($result['headers']['http_code'] === 400) {
                    $this->sendApiRequest(
                        "ecommerce/stores/{$this->context->shop->id}/orders/{$order->id}",
                        'PATCH',
                        $data
                    );
                }
            } catch (Exception $exception) {
                $this->context->controller->errors[] = "[SQUALOMAIL] :{$exception->getMessage()}";
                PrestaShopLogger::addLog("[SQUALOMAIL] :{$exception->getMessage()}");
            }
        }
    }

    public function hookActionValidateOrder($params)
    {
        if (isset($params['order']) && is_subclass_of($params['order'], 'OrderCore') && $this->isApiKeySet()) {
            try {
                $order = new Order($params['order']->id, $this->context->language->id);
                $orderSyncCommand = new \PrestaChamps\SqualomailModule\Commands\OrderSyncCommand(
                    $this->context,
                    $this->getApiClient(),
                    array($params['order']->id)
                );
                $orderSyncCommand->execute();
                $this->sendApiRequest(
                    "ecommerce/stores/{$this->context->shop->id}/carts/$order->id_cart",
                    'DELETE'
                );
            } catch (Exception $exception) {
                $this->context->controller->errors[] = "[SQUALOMAIL] :{$exception->getMessage()}";
                PrestaShopLogger::addLog("[SQUALOMAIL] :{$exception->getMessage()}");
            }
        }
    }

    /**
     * Delete the objects from the SqualoMail account also
     *
     * @param $params
     */
    public function hookActionProductUpdate($params)
    {   
        try {
            if (isset($params['product']) && $this->isApiKeySet()) {
                $product = $params['product'];
                if (is_a($product, 'ProductCore')) {
                    $this->updateProductCategories($product->id);

                    /**
                     * @var $product Product
                     */
                    $service = new \PrestaChamps\SqualomailModule\Commands\ProductSyncCommand(
                        $this->context,
                        new \DrewM\SqualoMail\SqualoMail(\Configuration::get(\SqualomailModuleConfig::SQUALOMAIL_API_KEY)),
                        array($product->id)
                    );

                    if ($product->isNew()) {
                        $service->setMethod($service::SYNC_METHOD_POST);
                    } else {
                        $service->setMethod($service::SYNC_METHOD_PATCH);
                    }

                    $service->execute();
                }
            }
        } catch (Exception $exception) {
            $this->context->controller->errors[] = $exception->getMessage();
            PrestaShopLogger::addLog(
                "SQUALOMAIL_ERROR: {$exception->getMessage()}",
                1,
                $exception->getCode(),
                PrestaChamps\SqualomailModule\Commands\ProductSyncCommand::class,
                null,
                true
            );
        }
    }

    public function hookActionCategoryAdd($params)
    {
        $this->categoryAddEdit($params, "POST");
    }

    public function hookActionCategoryUpdate($params)
    {
        $this->categoryAddEdit($params, "PUT");
    }

    private function categoryAddEdit($params, $method)
    {
        try {
            if (isset($params['category']) && $this->isApiKeySet()) {
                $category = $params['category'];

                $url = "ecommerce/stores/{$this->context->shop->id}/categories";
                $data = array(
                    "handle" => $category->link_rewrite[1],
                    "title" => $category->name[1],
                    "product_ids" => [],
                );

                if ($method === "PUT") {
                    $url .= "/{$category->id}";
                    $products = \Product::getProducts($this->context->language->id, 0, NULL, 'id_product', 'ASC', $category->id);
                    $data["product_ids"] = array_column($products, "id_product");
                }

                if ($method === "POST") {
                    $data["id"] = $category->id;
                }

                $result = $this->sendApiRequest($url, $method, $data);
            }
        } catch (Exception $e) {
            PrestaShopLogger::addLog("[SQUALOMAIL] :{$e->getMessage()}");
        }  
    }

    private function updateProductCategories($productId)
    {
        try {
            $response = $this->getApiClient()->get("ecommerce/stores/{$this->context->shop->id}/categories?count=1000&offset=0");
            if ($this->getApiClient()->success() && isset($response["categories"])) {
                $categoriesToUpdate = array();
                $productCategoriesIds = array();

                $lang = $this->context->language->id;
                $productCategories = \Product::getProductCategoriesFull($productId, $lang);
                foreach($productCategories as $category) {
                    array_push($productCategoriesIds, $category["id_category"]);
                }

                foreach($response["categories"] as $category) {
                    foreach($productCategories as $productCategory) {
                        $alreadySynced = false;
                        if ($productCategory["id_category"] == $category["id"]) {
                            $alreadySynced = true;
                        }

                        if (!$alreadySynced) {
                            array_push($categoriesToUpdate, $productCategory["id_category"]);
                        }
                    }

                    if (isset($category["products"])) {
                        foreach($category["products"] as $product) {
                            if ($product["id"] == $productId && !in_array($category["id"], $productCategoriesIds)) {
                                array_push($categoriesToUpdate, $category["id"]);
                            }
                        }
                    }
                }

                $command = new \PrestaChamps\SqualomailModule\Commands\CategoriesSyncCommand(
                    $this->context,
                    $this->getApiClient(),
                    $categoriesToUpdate
                );
                $command->setMethod($command::SYNC_METHOD_PUT);
                $command->execute();
            }
        }
         catch (Exception $e) {
            PrestaShopLogger::addLog("[SQUALOMAIL] :{$e->getMessage()}");
        }  
    }

    public function hookActionCategoryDelete($params)
    {
        if ($this->isApiKeySet()) {
            try {
                $objectId = $params["category"]->id;
                $this->getApiClient()->delete("ecommerce/stores/{$this->context->shop->id}/categories/{$objectId}");
            } catch (Exception $e) {
                $this->context->controller->errors[] = "[SQUALOMAIL] :{$e->getMessage()}";
                PrestaShopLogger::addLog("[SQUALOMAIL] :{$e->getMessage()}");
            }
        }
    }

    /**
     * Delete the objects from the SqualoMail account also
     *
     * @param $object
     */
    public function hookActionObjectDeleteAfter($object)
    {
        if (is_subclass_of($object['object'], 'ProductCore') && $this->isApiKeySet()) {
            $objectId = $object['object']->id;
            try {
                $this->getApiClient()->delete("ecommerce/stores/{$this->context->shop->id}/products/{$objectId}");
            } catch (Exception $e) {
                $this->context->controller->errors[] = "[SQUALOMAIL] :{$e->getMessage()}";
                PrestaShopLogger::addLog("[SQUALOMAIL] :{$e->getMessage()}");
            }
        }
    }

    /**
     * Sync the object updates to Squalomail
     *
     * @param $object
     */
    public function hookActionObjectUpdateAfter($object)
    {
        if (is_subclass_of($object['object'], 'CustomerCore') && $this->isApiKeySet()) {
            try {
                $url = "ecommerce/stores/{$this->context->shop->id}/customers/{$object['object']->id}";
                $data = (new \PrestaChamps\SqualomailModule\Formatters\CustomerFormatter($object['object'], $this->context))
                    ->format();
                $this->sendApiRequest($url, 'PUT', $data);
            } catch (Exception $exception) {
                $this->context->controller->errors[] = $exception->getMessage();
                PrestaShopLogger::addLog(
                    "[SQUALOMAIL]: {$exception->getMessage()}",
                    1,
                    $exception->getCode(),
                    PrestaChamps\SqualomailModule\Commands\CustomerSyncCommand::class,
                    null,
                    true
                );
            }
        }

        if (is_subclass_of($object['object'], 'ShopCore') && $this->isApiKeySet()) {
            try {
                $service = new \PrestaChamps\SqualomailModule\Commands\StoreSyncCommand(
                    $this->context,
                    $this->getApiClient(),
                    array($object['object']->id)
                );
                $service->setSyncMode($service::SYNC_MODE_REGULAR);
                $service->setMethod($service::SYNC_METHOD_PATCH);
                $service->execute();
            } catch (Exception $exception) {
                $this->context->controller->errors[] = $exception->getMessage();
                PrestaShopLogger::addLog(
                    "[SQUALOMAIL]: {$exception->getMessage()}",
                    1,
                    $exception->getCode(),
                    \PrestaChamps\SqualomailModule\Commands\StoreSyncCommand::class,
                    null,
                    true
                );
            }
        }
    }

    /**
     * @param $params
     *
     * @return string
     */
    public function hookDisplayAdminOrderContentOrder($params)
    {
        if ($this->isApiKeySet()) {
            try {
                /**
                 * @var $order Order
                 */
                $order = $params['order'];
                $response = $this->getApiClient()->get("ecommerce/stores/{$order->id_shop}/orders/{$order->id}");
                if ($this->getApiClient()->success()) {
                    $this->context->smarty->assign(array(
                        'order' => $response,
                    ));
                    return $this->context->smarty->fetch(
                        $this->getLocalPath() . 'views/templates/admin/sqm-order-detail-tab-content.tpl'
                    );
                }

                return $this->context->smarty->fetch(
                    $this->getLocalPath() . 'views/templates/admin/sqm-order-detail-tab-content-empty.tpl'
                );
            } catch (Exception $exception) {
                $this->context->controller->errors[] =
                    $this->l("Unable to fetch SqualoMail order: {$exception->getMessage()}");
            }
        }
        return '';
    }

    /**
     * @param $params
     *
     * @return string
     * @throws SmartyException
     */
    public function hookDisplayAdminOrderTabOrder($params)
    {
        return $this->context->smarty->fetch(
            $this->getLocalPath() . '/views/templates/admin/sqm-order-detail-tab-title.tpl'
        );
    }

    /**
     * @throws SmartyException
     */
    public function hookDisplayBackOfficeTop()
    {
        if ($this->context->controller->controller_name === 'AdminCarts' &&
            isset($_REQUEST['viewcart']) && $this->isApiKeySet()) {
            $cart = new Cart(Tools::getValue('id_cart'));
            $response = $this->getApiClient()->get("ecommerce/stores/{$cart->id_shop}/carts/{$cart->id}");
            if ($this->getApiClient()->success()) {
                $this->context->smarty->assign(array(
                    'cart' => $response,
                ));
                $this->context->controller->content .=
                    $this->context->smarty->fetch(
                        $this->getLocalPath() . 'views/templates/admin/sqm-cart-detail.tpl'
                    );
            }
        }
    }

    public function hookDisplayFooterBefore($params)
    {
        try {
            \PrestaChamps\SqualomailModule\Hooks\Display\FooterBefore::run(
                $params,
                $this->getApiClient(),
                $this->context
            )->newsletterBlockRegistration();
        } catch (Exception $exception) {
            PrestaShopLogger::addLog("[SQUALOMAIL] :{$exception->getMessage()}");
        }
    }

    protected function isApiKeySet()
    {
        return !empty(Configuration::get(SqualomailModuleConfig::SQUALOMAIL_API_KEY));
    }

    public function hookActionCustomerAccountAdd($params)
    {
        try {
            $customer = $this->getCustomerFromHookParam($params);
            \PrestaChamps\SqualomailModule\Hooks\Action\Customer\AccountAdd::run(
                $this->context,
                $this->getApiClient(),
                $customer
            );
        } catch (Exception $exception) {
            PrestaShopLogger::addLog("[SQUALOMAIL] :{$exception->getMessage()}");
        }
    }

    public function hookActionCustomerAccountUpdate($params)
    {
        try {
            $customer = $this->getCustomerFromHookParam($params);
            \PrestaChamps\SqualomailModule\Hooks\Action\Customer\AccountUpdate::run(
                $this->context,
                $this->getApiClient(),
                $customer
            );
        } catch (Exception $exception) {
            PrestaShopLogger::addLog("[SQUALOMAIL] :{$exception->getMessage()}");
        }
    }

    /**
     * @param $hookParams
     *
     * @return Customer
     * @throws Exception
     */
    private function getCustomerFromHookParam($hookParams)
    {
        if (isset($hookParams['customer']) && $hookParams['customer'] instanceof CustomerCore) {
            return $hookParams['customer'];
        }

        if (isset($hookParams['newCustomer']) && $hookParams['newCustomer'] instanceof CustomerCore) {
            return $hookParams['newCustomer'];
        }

        throw new Exception("Can't get Customer from hook");
    }
}