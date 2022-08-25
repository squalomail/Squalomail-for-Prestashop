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
use \PrestaChamps\SqualomailModule\Formatters\StoreFormatter;

/**
 * Class StoreSyncCommand
 *
 * @package PrestaChamps\SqualomailModule\Commands
 */
class StoreSyncCommand extends BaseApiCommand
{
    protected $context;
    protected $stores;
    protected $squalomail;
    protected $batch;
    protected $batchPrefix = '';

    /**
     * ProductSyncService constructor.
     *
     * @param \Context  $context
     * @param SqualoMail $squalomail
     * @param array     $storeIds
     */
    public function __construct(\Context $context, SqualoMail $squalomail, $storeIds = array())
    {
        $this->context = $context;
        $this->squalomail = $squalomail;
        $this->batchPrefix = uniqid('STORE_SYNC', true);
        $this->batch = $this->squalomail->new_batch($this->batchPrefix);
        $this->stores = $storeIds;
    }

    public function execute()
    {
        $shopId = \Configuration::get(\SqualomailModuleConfig::SQUALOMAIL_API_KEY);

        $this->responses = array();
        if ($this->syncMode == self::SYNC_MODE_REGULAR) {
            foreach ($this->stores as $storeId) {
                $formatted = new StoreFormatter(new \Shop($storeId), $this->context);
                if ($this->method === self::SYNC_METHOD_POST) {
                    $this->squalomail->post('/ecommerce/stores', $formatted->format());
                }
                if ($this->method === self::SYNC_METHOD_PATCH) {
                    $data = $formatted->format();
                    // SQM does not support changing the list id, so it must be unset
                    unset($data['list_id']);
                    $this->squalomail->patch("/ecommerce/stores/{$shopId}", $data);
                }
                if ($this->method === self::SYNC_METHOD_DELETE) {
                    $this->squalomail->delete("/ecommerce/stores/{$shopId}");
                }
                $this->responses[] = $this->squalomail->getLastResponse();
            }
        }
        if ($this->syncMode == self::SYNC_MODE_BATCH) {
            $batch = $this->squalomail->new_batch();
            foreach ($this->stores as $storeId) {
                $formatted = new StoreFormatter(new \Shop($storeId), $this->context);
                if ($this->method === 'POST') {
                    $batch->post("{$this->batchPrefix}_{$shopId}", '/ecommerce/stores', $formatted->format());
                }
                if ($this->method === 'PATCH') {
                    $data = $formatted->format();
                    // SQM does not support changing the list id, so it must be unset
                    unset($data['list_id']);
                    $batch->patch("{$this->batchPrefix}_{$shopId}", "/ecommerce/stores/{$shopId}", $data);
                }
                if ($this->method === 'DELETE') {
                    $batch->delete("{$this->batchPrefix}_{$shopId}", "/ecommerce/stores/{$shopId}");
                }
                $this->responses[] = $this->squalomail->getLastResponse();
            }
            $this->responses[] = $batch->execute();
        }

        return $this->responses;
    }
}
