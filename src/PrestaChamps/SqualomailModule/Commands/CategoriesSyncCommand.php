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
use PrestaChamps\SqualomailModule\Formatters\CategoryFormatter;

/**
 * Class CategoriesSyncCommand
 *
 * @package PrestaChamps\SqualomailModule\Commands
 */
class CategoriesSyncCommand extends BaseApiCommand
{
    protected $context;
    protected $categoryIds;
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
     * CategoriesSyncCommand constructor.
     *
     * @param \Context  $context
     * @param SqualoMail $squalomail
     * @param           $categoryIds
     */
    public function __construct(\Context $context, SqualoMail $squalomail, $categoryIds)
    {
        $this->context = $context;
        $this->squalomail = $squalomail;
        $this->batchPrefix = uniqid("CATEGORY_SYNC_{$this->method}_", true);
        $this->batch = $this->squalomail->new_batch($this->batchPrefix);
        $this->categoryIds = $categoryIds;
    }

    /**
     * @return array|false
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    public function execute()
    {
        $this->buildCategories();
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
    protected function buildCategories()
    {
        foreach ($this->categoryIds as $key => $category) {
            $lang = $this->context->language->id;
            $category = new \Category($category, $lang);
            $categoryFormatter = new CategoryFormatter(
                $category,
                $this->context
            );

            $shopId = \Configuration::get(\SqualomailModuleConfig::SQUALOMAIL_API_KEY);

            if ($this->method === self::SYNC_METHOD_POST) {
                if ($this->syncMode == self::SYNC_MODE_BATCH) {
                    $this->batch->post(
                        $this->batchPrefix . '_' . $key,
                        "/ecommerce/stores/{$shopId}/categories",
                        $categoryFormatter->format()
                    );
                } else {
                    $this->commands[$category->id] = array(
                        'route' => "/ecommerce/stores/{$shopId}/categories",
                        'data' => $categoryFormatter->format(),
                    );
                }
            } elseif ($this->method === self::SYNC_METHOD_PUT) {
                if ($this->syncMode == self::SYNC_MODE_BATCH) {
                    $this->batch->put(
                        $this->batchPrefix . '_' . $key,
                        "/ecommerce/stores/{$shopId}/categories/{$category->id}",
                        $categoryFormatter->format()
                    );
                } else {
                    $this->commands[$category->id] = array(
                        'route' => "/ecommerce/stores/{$shopId}/categories/{$category->id}",
                        'data' => $categoryFormatter->format(),
                    );
                }
            } else {
                if ($this->syncMode == self::SYNC_MODE_BATCH) {
                    $this->batch->delete(
                        $this->batchPrefix . '_' . $key,
                        "/ecommerce/stores/{$shopId}/categories/{$category->id}"
                    );
                } else {
                    $this->commands[$category->id] = array(
                        'route' => "/ecommerce/stores/{$shopId}/categories/{$category->id}",
                        'data' => array(),
                    );
                }
            }
        }
    }
}
