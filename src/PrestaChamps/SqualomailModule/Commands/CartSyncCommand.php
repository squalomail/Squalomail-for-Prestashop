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

use Cart;
use Context;
use Customer;
use DrewM\SqualoMail\SqualoMail;
use Exception;
use PrestaChamps\SqualomailModule\Formatters\CartFormatter;

/**
 * Class CartSyncCommand
 * @package PrestaChamps\SqualomailModule\Commands
 */
class CartSyncCommand extends BaseApiCommand
{
    protected $context;
    protected $cartIds;
    protected $squalomail;
    protected $batch;
    protected $batchPrefix = '';
    protected $triggerDoubleOptIn = false;

    public function __construct(Context $context, SqualoMail $squalomail, $cartIds = array())
    {
        $this->context = $context;
        $this->squalomail = $squalomail;
        $this->batchPrefix = uniqid('CART_SYNC', true);
        $this->batch = $this->squalomail->new_batch($this->batchPrefix);
        $this->cartIds = $cartIds;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function execute()
    {
        $shopId = \Configuration::get(\SqualomailModuleConfig::SQUALOMAIL_API_KEY);

        $this->responses = array();
        if ((int)$this->syncMode === self::SYNC_MODE_REGULAR) {
            foreach ($this->cartIds as $cartId) {
                $cart = new Cart($cartId);
                $customer = new Customer($cart->id_customer);
                $formatted = (new CartFormatter($cart, $customer, $this->context))->format();
                if ($this->method === self::SYNC_METHOD_POST) {
                    $this->squalomail->post(
                        "/ecommerce/stores/{$shopId}/carts",
                        $formatted
                    );
                }
                if ($this->method === self::SYNC_METHOD_PATCH) {
                    $this->squalomail->patch(
                        "/ecommerce/stores/{$shopId}/carts/{$cart->id}",
                        $formatted
                    );
                }
                if ($this->method === self::SYNC_METHOD_DELETE) {
                    $this->squalomail->delete(
                        "/ecommerce/stores/{$shopId}/carts/{$cart->id}"
                    );
                }
                $this->responses[] = $this->squalomail->getLastResponse();
            }
        }

        if ((int)$this->syncMode === self::SYNC_MODE_BATCH) {
            throw new Exception("Batch mode not supported yet!");
        }

        return $this->responses;
    }

    protected function getCartExists($cartId)
    {
        $shopId = \Configuration::get(\SqualomailModuleConfig::SQUALOMAIL_API_KEY);

        $this->squalomail->get(
            "/ecommerce/stores/{$shopId}/carts/{$cartId}",
            array('fields' => array('id'))
        );

        if ($this->squalomail->success()) {
            return true;
        }

        return false;
    }
}
