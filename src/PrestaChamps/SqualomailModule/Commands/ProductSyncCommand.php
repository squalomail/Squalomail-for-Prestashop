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

namespace PrestaChamps\SqualomailModule\Commands;

use DrewM\SqualoMail\SqualoMail;
use PrestaChamps\SqualomailModule\Formatters\ProductFormatter;

/**
 * Class ProductSyncService
 *
 * @package PrestaChamps\SqualomailModule\Exceptions
 */
class ProductSyncCommand extends BaseApiCommand
{
    protected $context;
    protected $productIds;
    protected $squalomail;
    protected $batch;
    protected $batchPrefix = '';
    protected $method      = 'POST';
    protected $commands    = array();

    /**
     * @var \Category[]
     */
    protected $categoryCache = array();

    /**
     * ProductSyncService constructor.
     *
     * @param \Context  $context
     * @param SqualoMail $squalomail
     * @param           $productIds
     */
    public function __construct(\Context $context, SqualoMail $squalomail, $productIds)
    {
        $this->context = $context;
        $this->squalomail = $squalomail;
        $this->batchPrefix = uniqid("PRODUCT_SYNC_{$this->method}_", true);
        $this->batch = $this->squalomail->new_batch($this->batchPrefix);
        $this->productIds = $productIds;
    }

    /**
     * @return array|false
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    public function execute()
    {
        $this->buildProducts();
        if ($this->method == self::SYNC_MODE_BATCH) {
            return $this->batch->execute();
        }
        $method = \Tools::strtolower($this->method);
        foreach ($this->commands as $entityId => $params) {
            try {
                $this->responses[$entityId] = $this->squalomail->$method($params['route'], $params['data']);
            } catch (\Exception $exception) {
                $this->responses[$entityId] = $this->squalomail->getLastResponse();
                continue;
            }
        }
        return $this->responses;
    }

    /**
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    protected function buildProducts()
    {
        foreach ($this->productIds as $key => $product) {
            $product = new \Product($product, false, $this->context->language->id);
            $category = $this->getCategory($product->getDefaultCategory());
            $productFormatter = new ProductFormatter(
                $product,
                $category,
                $this->context
            );

            $shopId = \Configuration::get(\SqualomailModuleConfig::SQUALOMAIL_API_KEY);

            if ($this->method === self::SYNC_METHOD_POST) {
                if ($this->syncMode == self::SYNC_MODE_BATCH) {
                    $this->batch->post(
                        $this->batchPrefix . '_' . $key,
                        "/ecommerce/stores/{$shopId}/products",
                        $productFormatter->format()
                    );
                } else {
                    $this->commands[$product->id] = array(
                        'route' => "/ecommerce/stores/{$shopId}/products",
                        'data' => $productFormatter->format(),
                    );
                }
            } elseif ($this->method === self::SYNC_METHOD_PATCH) {
                if ($this->syncMode == self::SYNC_MODE_BATCH) {
                    $this->batch->patch(
                        $this->batchPrefix . '_' . $key,
                        "/ecommerce/stores/{$shopId}/products/{$product->id}",
                        $productFormatter->format()
                    );
                } else {
                    $this->commands[$product->id] = array(
                        'route' => "/ecommerce/stores/{$shopId}/products/{$product->id}",
                        'data' => $productFormatter->format(),
                    );
                }
            } else {
                if ($this->syncMode == self::SYNC_MODE_BATCH) {
                    $this->batch->delete(
                        $this->batchPrefix . '_' . $key,
                        "/ecommerce/stores/{$shopId}/products/{$product->id}"
                    );
                } else {
                    $this->commands[$product->id] = array(
                        'route' => "/ecommerce/stores/{$shopId}/products/{$product->id}",
                        'data' => array(),
                    );
                }
            }
        }
    }

    /**
     * It's a good idea to store categories in a cache to prevent multiple and unnecessary DB calls
     *
     * @param $categoryId
     *
     * @return \Category
     */
    protected function getCategory($categoryId)
    {
        // Because PrestaShop, that's why
        if (!is_scalar($categoryId)) {
            $categoryId = $categoryId['id_category_default'];
        }

        if (isset($this->categoryCache[$categoryId])) {
            return $this->categoryCache[$categoryId];
        }
        $this->categoryCache[$categoryId] = new \Category(
            $categoryId,
            $this->context->language->id,
            $this->context->shop->id
        );

        return $this->categoryCache[$categoryId];
    }

    public function getBatchId()
    {
        return $this->batchPrefix;
    }
}
