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
 * Class AdminSqualomailModuleConfigController
 *
 * @property Squalomailmodule $module
 */
class AdminSqualomailModuleConfigController extends ModuleAdminController
{
    public $bootstrap = 'true';

    private static $formName = 'SqualomailModule_config_form';


    /**
     * Save a configuration value
     *
     * @param string $configKey
     */
    public function saveConfigValue($configKey)
    {
        $value = Tools::getValue($configKey);
        if (!is_scalar($value)) {
            $value = json_encode($value);
        }
        Configuration::updateValue($configKey, $value);

        $languages = Language::getLanguages(false, false, false);
        foreach ($languages as $language) {
            if (Tools::getValue($configKey . "_{$language['id_lang']}", false)) {
                Configuration::updateValue(
                    $configKey,
                    array($language['id_lang'] => (string)Tools::getValue($configKey . '_' . $language['id_lang'], '')),
                    true
                );
            }
        }
    }


    /**
     * Process, normalise and save configuration values
     */
    public function processConfiguration()
    {
        foreach ($_REQUEST as $key => $value) {
            $normalizedKey = preg_replace('/_\d{1,}$/', '', $key);
            unset($value);
            $this->saveConfigValue($normalizedKey);
        }
        Configuration::updateValue(SqualomailModuleConfig::SQUALOMAIL_SCRIPT_VERIFIED, '0');
        Configuration::loadConfiguration();

        $this->confirmations[] = $this->l('Configuration updated');
    }

    public function processDeleteEcommerceData()
    {
        try {
            $shops = array_column(Shop::getShops(true), 'id_shop');
            $command = new \PrestaChamps\SqualomailModule\Commands\StoreSyncCommand(
                $this->context,
                $this->module->getApiClient(),
                $shops
            );
            $command->setMethod($command::SYNC_METHOD_DELETE);
            $command->setSyncMode($command::SYNC_MODE_REGULAR);
            $command->execute();
            $this->confirmations[] = $this->l('E-commerce data has been deleted');
        } catch (Exception $exception) {
            $this->errors[] = "Error during deleting e-commerce data";
        }
    }

    /**
     * @return string
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function renderStateMappingForm()
    {
        $orderStates = OrderState::getOrderStates($this->context->language->id);
        $fields_form = array(
            'form' => array(
                'legend' => array('title' => $this->l('Order state mapping'), 'icon' => 'icon-envelope'),
                'input'  => array(
                    array(
                        'type'     => 'select',
                        'multiple' => true,
                        'label'    => $this->l('Status for pending'),
                        'name'     => SqualomailModuleConfig::STATUSES_FOR_PENDING,
                        'size'     => count($orderStates),
                        'options'  => array(
                            'query' => $orderStates,
                            'id'    => 'id_order_state',
                            'name'  => 'name',
                        ),
                    ),
                    array(
                        'type'     => 'select',
                        'multiple' => true,
                        'label'    => $this->l('Status for refunded'),
                        'name'     => SqualomailModuleConfig::STATUSES_FOR_REFUNDED,
                        'size'     => count($orderStates),
                        'options'  => array(
                            'query' => $orderStates,
                            'id'    => 'id_order_state',
                            'name'  => 'name',
                        ),
                    ),
                    array(
                        'type'     => 'select',
                        'multiple' => true,
                        'label'    => $this->l('Status for cancelled'),
                        'name'     => SqualomailModuleConfig::STATUSES_FOR_CANCELLED,
                        'size'     => count($orderStates),
                        'options'  => array(
                            'query' => $orderStates,
                            'id'    => 'id_order_state',
                            'name'  => 'name',
                        ),
                    ),
                    array(
                        'type'     => 'select',
                        'multiple' => true,
                        'label'    => $this->l('Status for shipped'),
                        'name'     => SqualomailModuleConfig::STATUSES_FOR_SHIPPED,
                        'size'     => count($orderStates),
                        'options'  => array(
                            'query' => $orderStates,
                            'id'    => 'id_order_state',
                            'name'  => 'name',
                        ),
                    ),
                    array(
                        'type'     => 'select',
                        'multiple' => true,
                        'label'    => $this->l('Status for paid'),
                        'name'     => SqualomailModuleConfig::STATUSES_FOR_PAID,
                        'size'     => count($orderStates),
                        'options'  => array(
                            'query' => $orderStates,
                            'id'    => 'id_order_state',
                            'name'  => 'name',
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-primary btn-sqm pull-right',
                    'id'    => 'state-save-btn'
                ),
            ),
        );
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = 'states';
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ?: 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = self::$formName;
        $helper->currentIndex = $this->context->link->getAdminLink($this->controller_name, false);
        $helper->token = Tools::getAdminTokenLite($this->controller_name);
        $helper->tpl_vars = array(
            'fields_value' => SqualomailModuleConfig::getConfigurationValues(),
            'languages'    => $this->context->controller->getLanguages(),
            'id_language'  => $this->context->language->id,
        );

        return $helper->generateForm(array($fields_form));
    }

    /**
     * @return string
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function renderForm()
    {
        try {
            $lists = $this->module->getApiClient()->get('lists', array('fields' => 'lists.name,lists.id'));
        } catch (Exception $exception) {
            $this->errors[] = $exception->getMessage();
            $lists = array(
                'lists' => array(),
            );
        }
        $fields_form = array(
            'form' => array(
                'legend' => array('title' => $this->l('General settings'), 'icon' => 'icon-envelope'),
                'input'  => array(
                    array(
                        'type'     => 'select',
                        'multiple' => false,
                        'label'    => $this->l('Product image size'),
                        'name'     => SqualomailModuleConfig::PRODUCT_IMAGE_SIZE,
                        'desc'     => $this->module->l('Remember to resync products if you change this'),
                        'options'  => array(
                            'query' => $this->getImageSizes(),
                            'id'    => 'name',
                            'name'  => 'name',
                        ),
                    ),
                    //                    [
                    //                        'type'     => 'text',
                    //                        'label'    => $this->l('API Key'),
                    //                        'name'     => SqualomailModuleConfig::SQUALOMAIL_API_KEY,
                    //                        'lang'     => false,
                    //                        'required' => true,
                    //                        'hint'     => $this->l('API Key'),
                    //                    ],
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-primary btn-sqm pull-right',
                ),
            ),
        );
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = 'misc';
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ?: 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = self::$formName;
        $helper->currentIndex = $this->context->link->getAdminLink($this->controller_name, false);
        $helper->token = Tools::getAdminTokenLite($this->controller_name);
        $helper->tpl_vars = array(
            'fields_value' => SqualomailModuleConfig::getConfigurationValues(),
            'languages'    => $this->context->controller->getLanguages(),
            'id_language'  => $this->context->language->id,
        );

        return $helper->generateForm(array($fields_form));
    }

    /**
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     * @throws SmartyException
     */
    public function initContent()
    {
        $this->addCSS($this->module->getLocalPath() . 'views/css/main.css');
        if (Tools::isSubmit(self::$formName)) {
            $this->processConfiguration();
        }
        $this->content .= $this->renderNavBar();
        $this->content .= $this->renderAccountInfo();
        $this->content .= $this->renderForm();
        $this->content .= $this->renderStateMappingForm();
        $this->content .= $this->renderDeleteEcommerceData();
        parent::initContent();
    }

    /**
     * @return string
     */
    public function renderAccountInfo()
    {
        try {
            $info = $this->module->getApiClient()->get('');
            if (!$this->module->getApiClient()->success()) {
                return '';
            }
            $this->context->smarty->assign(array(
                'info' => $info,
            ));
            return $this->context->smarty->fetch(
                $this->module->getLocalPath() . 'views/templates/admin/config/account-info.tpl'
            );
        } catch (Exception $exception) {
            return '';
        }
    }

    /**
     * @return string
     */
    public function renderDeleteEcommerceData()
    {
        try {
            $info = $this->module->getApiClient()->get('');
            if (!$this->module->getApiClient()->success()) {
                return '';
            }
            $this->context->smarty->assign(array(
                'info' => $info,
            ));
            return $this->context->smarty->fetch(
                $this->module->getLocalPath() . 'views/templates/admin/config/delete-ecommerce-data.tpl'
            );
        } catch (Exception $exception) {
            return '';
        }
    }

    /**
     * @return string
     * @throws SmartyException
     */
    public function renderNavBar()
    {
        return $this->context->smarty->fetch(
            $this->module->getLocalPath() . 'views/templates/admin/config/navbar.tpl'
        );
    }

    /**
     * Get the available image sizes
     *
     * @return array
     */
    private function getImageSizes()
    {
        $query = new DbQuery();
        $query->select('name, width, height');
        $query->from('image_type');
        $query->where('products = 1');
        try {
            return Db::getInstance()->executeS($query);
        } catch (Exception $exception) {
            $this->errors[] = $exception->getMessage();
            return array();
        }
    }
}
