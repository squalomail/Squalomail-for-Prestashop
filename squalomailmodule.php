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
            'name'              => 'Squalo Config',
            'class_name'        => 'squalomailmodule',
            'visible'           => true,
            'parent_class_name' => 0,
        ),
        array(
            'is_root'           => false,
            'name'              => 'Squalo Config',
            'class_name'        => 'AdminSqualomailModuleConfig',
            'visible'           => true,
            'parent_class_name' => 'squalomailmodule',
        ),
        array(
            'is_root'           => false,
            'name'              => 'Squalo Setup Wizard',
            'class_name'        => 'AdminSqualomailModuleWizard',
            'visible'           => true,
            'parent_class_name' => 'squalomailmodule',
        ),
        array(
            'is_root'           => false,
            'name'              => 'Squalo List',
            'class_name'        => 'AdminSqualomailModuleLists',
            'visible'           => false,
            'parent_class_name' => 'squalomailmodule',
        ),
        array(
            'is_root'           => false,
            'name'              => 'Squalo Batches',
            'class_name'        => 'AdminSqualomailModuleBatches',
            'visible'           => false,
            'parent_class_name' => 'squalomailmodule',
        ),
        array(
            'is_root'           => false,
            'name'              => 'Squalo Carts',
            'class_name'        => 'AdminSqualomailModuleCarts',
            'visible'           => false,
            'parent_class_name' => 'squalomailmodule',
        ),
        array(
            'is_root'           => false,
            'name'              => 'Squalo Customers',
            'class_name'        => 'AdminSqualomailModuleCustomers',
            'visible'           => false,
            'parent_class_name' => 'squalomailmodule',
        ),
        array(
            'is_root'           => false,
            'name'              => 'Squalo Orders',
            'class_name'        => 'AdminSqualomailModuleOrders',
            'visible'           => false,
            'parent_class_name' => 'squalomailmodule',
        ),
        array(
            'is_root'           => false,
            'name'              => 'Squalo Products',
            'class_name'        => 'AdminSqualomailModuleProducts',
            'visible'           => false,
            'parent_class_name' => 'squalomailmodule',
        ),
        array(
            'is_root'           => false,
            'name'              => 'Squalo Categories',
            'class_name'        => 'AdminSqualomailModuleCategories',
            'visible'           => false,
            'parent_class_name' => 'squalomailmodule',
        ),
        array(
            'is_root'           => false,
            'name'              => 'Squalo Stores',
            'class_name'        => 'AdminSqualomailModuleStores',
            'visible'           => false,
            'parent_class_name' => 'squalomailmodule',
        ),
        array(
            'is_root'           => false,
            'name'              => 'Squalo Sync',
            'class_name'        => 'AdminSqualomailModuleSync',
            'visible'           => false,
            'parent_class_name' => 'squalomailmodule',
        ),
        array(
            'is_root'           => false,
            'name'              => 'Squalo Sites',
            'class_name'        => 'AdminSqualomailModuleSites',
            'visible'           => false,
            'parent_class_name' => 'squalomailmodule',
        ),
        array(
            'is_root'           => false,
            'name'              => 'Squalo Automations',
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
        $this->version = '2.0.9';
        $this->author = 'Squalo';
        $this->need_instance = 1;
        $this->bootstrap = true;
        $this->module_key = '793ebc5f330220c7fb7b817fe0d63a92';

        parent::__construct();

        $this->displayName = $this->trans('Squalo');
        $this->description = $this->trans('Official Squalo integration for PrestaShop');
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

        $installed = parent::install() &&
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
            $this->registerHook('actionObjectCartRuleUpdateAfter') &&
            $this->registerHook('displayFooterBefore') &&
            $this->registerHook('actionCustomerAccountAdd') &&
            $this->registerHook('actionCustomerAccountUpdate');

        return $installed;
    }

    /**
     * Verify the site script again whenever the module is switched back on
     *
     * @param bool $force_all
     *
     * @return bool
     */
    public function enable($force_all = false)
    {
        if (!parent::enable($force_all)) {
            return false;
        }

        $this->verifySiteScript();

        return true;
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
     * Render the SqualoMail tracking script in the front office footer
     *
     * Reads persisted configuration only. Whether a connected site exists is
     * resolved by verifySiteScript() on module activation and after a store
     * sync, so no API call happens while a customer waits for the page.
     *
     * @param $params
     *
     * @return string
     */
    public function hookDisplayFooter($params)
    {
        $site_script_url = Configuration::get(SqualomailModuleConfig::SQUALOMAIL_SITE_SCRIPT_URL);
        if (!$this->isApiKeySet() || empty($site_script_url)) {
            return '';
        }

        // json_encode emits the quoted JS string literal, escaping included
        $site_script = json_encode($site_script_url);

        return "<script id=\"mcjs\">!function(c,h,i,m,p){m=c.createElement(h),p=c.getElementsByTagName(h)[0],m.async=1,m.src=i,p.parentNode.insertBefore(m,p)}(document,\"script\",$site_script);</script>";
    }

    /**
     * Ask SqualoMail to verify the tracking script of the connected site
     *
     * This used to run on every front office request through
     * actionFrontControllerSetMedia, which meant a blocking remote call per page
     * view. It runs when the module is activated, after a store sync, and when
     * the module configuration is saved.
     *
     * @return bool True when the site script is verified, now or already
     */
    public function verifySiteScript()
    {
        if (!$this->isApiKeySet()) {
            return false;
        }

        // Same store id StoreSyncCommand writes: the API key, not the shop id
        $storeId = Configuration::get(SqualomailModuleConfig::SQUALOMAIL_API_KEY);

        try {
            $result = $this->getApiClient()->get("ecommerce/stores/{$storeId}");
            // A failed read must not wipe a script the footer is already rendering
            if (!$this->getApiClient()->success() || !is_array($result)) {
                return false;
            }

            $siteId = isset($result['connected_site']['site_foreign_id'])
                ? $result['connected_site']['site_foreign_id']
                : null;

            // Drives hookDisplayFooter, so it has to follow the store both ways
            Configuration::updateValue(
                SqualomailModuleConfig::SQUALOMAIL_SITE_SCRIPT_URL,
                isset($result['connected_site']['site_script']['url'])
                    ? $result['connected_site']['site_script']['url']
                    : ''
            );

            if (empty($siteId)) {
                Configuration::updateValue(SqualomailModuleConfig::SQUALOMAIL_SCRIPT_VERIFIED, '0');

                return false;
            }

            if (Configuration::get(SqualomailModuleConfig::SQUALOMAIL_SCRIPT_VERIFIED)) {
                return true;
            }

            (new \PrestaChamps\SqualomailModule\Commands\SiteVerifyCommand($this->getApiClient(), $siteId))
                ->execute();
            $this->sendApiRequest(
                "ecommerce/stores/{$storeId}",
                'POST',
                array('is_syncing' => false)
            );
            Configuration::updateValue(SqualomailModuleConfig::SQUALOMAIL_SCRIPT_VERIFIED, true);

            return true;
        } catch (Exception $exception) {
            PrestaShopLogger::addLog("[SQUALOMAIL] :{$exception->getMessage()}");

            return false;
        }
    }

    /**
     * Drop the cached tracking script so the footer stops rendering it
     */
    public function clearSiteScript()
    {
        Configuration::updateValue(SqualomailModuleConfig::SQUALOMAIL_SITE_SCRIPT_URL, '');
        Configuration::updateValue(SqualomailModuleConfig::SQUALOMAIL_SCRIPT_VERIFIED, '0');
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
                    $this->trans("Unable to fetch SqualoMail order: {$exception->getMessage()}");
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
        if (!$this->isApiKeySet()) {
            return;
        }

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