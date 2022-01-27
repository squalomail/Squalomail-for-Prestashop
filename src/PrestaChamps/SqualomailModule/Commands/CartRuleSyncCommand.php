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
 * @author    PrestaChamps <leo@prestachamps.com>
 * @copyright PrestaChamps
 * @license   commercial
 */

namespace PrestaChamps\SqualomailModule\Commands;

use DrewM\SqualoMail\SqualoMail;
use PrestaChamps\SqualomailModule\Formatters\PromoRuleFormatter;

/**
 * Class CartRuleSyncCommand
 *
 * @package PrestaChamps\SqualomailModule\Commands
 */
class CartRuleSyncCommand extends BaseApiCommand
{
    protected $context;
    protected $cartRules;
    protected $squalomail;
    protected $batch;
    protected $batchPrefix = '';

    /**
     * ProductSyncService constructor.
     *
     * @param \Context    $context
     * @param SqualoMail   $squalomail
     * @param \CartRule[] $cartRules
     */
    public function __construct(\Context $context, SqualoMail $squalomail, $cartRules = array())
    {
        $this->context = $context;
        $this->squalomail = $squalomail;
        $this->batchPrefix = uniqid('CART_RULE_SYNC', true);
        $this->batch = $this->squalomail->new_batch($this->batchPrefix);
        $this->cartRules = $cartRules;
    }

    public function execute()
    {
        $this->responses = array();
        if ($this->syncMode === self::SYNC_MODE_REGULAR) {
            foreach ($this->cartRules as $cartRule) {
                $formatted = new PromoRuleFormatter($cartRule, $this->context);
                if ($this->method === self::SYNC_METHOD_POST) {
                    $this->squalomail->post(
                        "ecommerce/stores/{$this->context->shop->id}/promo-rules",
                        $formatted->format()
                    );
                    $this->responses[] = $this->squalomail->getLastResponse();
                    $this->squalomail->post(
                        "ecommerce/stores/{$this->context->shop->id}/promo-rules/$cartRule->id/promo-codes",
                        $formatted->formatPromoCode()
                    );
                    $this->responses[] = $this->squalomail->getLastResponse();
                }
                if ($this->method === self::SYNC_METHOD_PATCH) {
                    $this->squalomail->patch(
                        "ecommerce/stores/{$this->context->shop->id}/promo-rules/{$cartRule->id}",
                        $formatted->format()
                    );
                    $this->responses[] = $this->squalomail->getLastResponse();
                    // @codingStandardsIgnoreStart
                    $this->squalomail->patch(
                        "ecommerce/stores/{$this->context->shop->id}/promo-rules/{$cartRule->id}/promo-codes/{$cartRule->id}",
                        $formatted->formatPromoCode()
                    );
                    // @codingStandardsIgnoreEnd
                    $this->responses[] = $this->squalomail->getLastResponse();
                }
                if ($this->method === self::SYNC_METHOD_DELETE) {
                    // @codingStandardsIgnoreStart
                    $this->squalomail->delete(
                        "ecommerce/stores/{$this->context->shop->id}/promo-rules/{$cartRule->id}/promo-codes/{$cartRule->id}"
                    );
                    // @codingStandardsIgnoreEnd
                    $this->responses[] = $this->squalomail->getLastResponse();
                    $this->squalomail->delete(
                        "ecommerce/stores/{$this->context->shop->id}/promo-rules/{$cartRule->id}"
                    );
                    $this->responses[] = $this->squalomail->getLastResponse();
                }
            }
        }
        if ($this->syncMode === self::SYNC_MODE_BATCH) {
            $batch = $this->squalomail->new_batch();
            foreach ($this->cartRules as $cartRule) {
                $formatted = new PromoRuleFormatter($cartRule, $this->context);
                if ($this->method === 'POST') {
                    $batch->post(
                        "{$this->batchPrefix}_{$cartRule->id}",
                        "ecommerce/stores/{$this->context->shop->id}/promo-rules",
                        $formatted->format()
                    );
                }
                if ($this->method === 'PATCH') {
                    $data = $formatted->format();
                    $batch->patch(
                        "{$this->batchPrefix}_{$cartRule->id}",
                        "ecommerce/stores/{$this->context->shop->id}/promo-rules/{$cartRule->id}",
                        $data
                    );
                }
                if ($this->method === 'DELETE') {
                    $batch->delete(
                        "{$this->batchPrefix}_{$cartRule->id}",
                        "ecommerce/stores/{$this->context->shop->id}/promo-rules/{$cartRule->id}"
                    );
                }
                $this->responses[] = $this->squalomail->getLastResponse();
            }
            $this->responses[] = $batch->execute();
        }

        return $this->responses;
    }
}
