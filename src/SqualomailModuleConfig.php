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

/**
 * Class SqualomailModuleConfig
 */
class SqualomailModuleConfig
{
    const SQUALOMAIL_API_KEY = 'module-squalomailmoduleconfig-squalomail-api-key';
    const SQUALOMAIL_SCRIPT_VERIFIED = 'module-squalomailmoduleconfig-script-verified';
    const SQUALOMAIL_LIST_ID = 'module-squalomailmoduleconfig-list-id';
    const SQUALOMAIL_LIST_NAME = 'module-squalomailmoduleconfig-list-id';

    const STATUSES_FOR_PAID = 'module-squalomailmoduleconfig-statuses-for-paid';
    const STATUSES_FOR_PENDING = 'module-squalomailmoduleconfig-statuses-for-pending';
    const STATUSES_FOR_REFUNDED = 'module-squalomailmoduleconfig-statuses-for-refunded';
    const STATUSES_FOR_CANCELLED = 'module-squalomailmoduleconfig-statuses-for-cancelled';
    const STATUSES_FOR_SHIPPED = 'module-squalomailmoduleconfig-statuses-for-shipped';

    const PRODUCT_IMAGE_SIZE = 'module-squalomailmoduleconfig-product-image-size';

    public static $jsonValues = array(
        self::STATUSES_FOR_PAID,
        self::STATUSES_FOR_PENDING,
        self::STATUSES_FOR_REFUNDED,
        self::STATUSES_FOR_CANCELLED,
        self::STATUSES_FOR_SHIPPED,
    );

    /** Required for PHP < 5.6 compatibility */
    public static $className = 'SqualomailModuleConfig';

    public static $multiLang = array(
    );

    /**
     * Save a config value
     *
     * @param $key
     * @param $value
     *
     * @return bool
     */
    public static function saveValue($key, $value)
    {
        return Configuration::updateValue($key, $value);
    }

    /**
     * Get configuration keys and values
     *
     * @return array
     */
    public static function getConfigurationValues()
    {
        try {
            $class = new ReflectionClass(static::$className);
            $values = array();
            foreach ($class->getConstants() as $constant) {
                if (is_string($constant)) {
                    if (in_array($constant, static::$multiLang, false)) {
                        static::getMultilangConfigValues($constant, $values);
                    } else {
                        $values[$constant] = Configuration::get($constant);
                    }
                }
                if (in_array($constant, static::$jsonValues, false)) {
                    $values[$constant . '[]'] = json_decode(Configuration::get($constant), true);
                }
            }
            return $values;
        } catch (Exception $exception) {
            return array();
        }
    }

    /**
     * Get a multilang config key (mainly used with the HelperForm class)
     *
     * @param $key
     * @param $values
     */
    protected static function getMultilangConfigValues($key, &$values)
    {
        $languages = Language::getLanguages(false, false, false);
        $values[$key] = array();
        foreach ($languages as $language) {
            $values[$key][$language['id_lang']] = Configuration::get($key, $language['id_lang']);
        }
    }

    /**
     * Decide if a config key exists in the DB or not, doesn't really care about multilang
     *
     * @param null $configKey
     *
     * @return bool
     * @throws PrestaShopDatabaseException
     */
    public static function configExists($configKey = null)
    {
        $query = new \DbQuery();
        $query->select('count(*)');
        $query->from('configuration');
        $query->where("name = '" . pSQL($configKey) . "'");

        return (int)Db::getInstance()->executeS($query) > 0;
    }

    public static function isApiKeySet()
    {
        return false;
    }
}
