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

namespace PrestaChamps\SqualomailModule\Hooks\Action\Customer;

use Context;
use Customer;
use DrewM\SqualoMail\SqualoMail;
use PrestaChamps\SqualomailModule\Commands\CartSyncCommand;
use PrestaChamps\SqualomailModule\Commands\CustomerSyncCommand;

/**
 * Invoked when a customer updates its account successfully
 *
 * @package PrestaChamps\SqualomailModule\Hooks\Action\Customer
 */
class AccountUpdate
{
    protected $context;
    protected $customer;
    protected $squalomail;

    /**
     * AccountUpdate constructor
     *
     * @param Customer $customer
     * @param SqualoMail $squalomail
     * @param Context $context
     */
    protected function __construct(Customer $customer, SqualoMail $squalomail, Context $context)
    {
        $this->context = $context;
        $this->customer = $customer;
        $this->squalomail = $squalomail;

        if ($customer->isGuest()) {
            $this->handleGuestCheckoutAbandonedMail();
        }
    }

    public static function run(Context $context, SqualoMail $squalomail, Customer $customer)
    {
        new static($customer, $squalomail, $context);
    }

    protected function handleGuestCheckoutAbandonedMail()
    {
        $this->syncCustomer();
        $this->syncCart();
    }

    protected function syncCustomer()
    {
        $command = new CustomerSyncCommand($this->context, $this->squalomail, array($this->customer->id));
        $command->setMethod(CustomerSyncCommand::SYNC_METHOD_PUT);
        $command->setSyncMode(CustomerSyncCommand::SYNC_MODE_REGULAR);
        $command->execute();
    }

    protected function syncCart()
    {
        if ($this->context->cart && $this->context->cart->nbProducts()) {
            $command = new CartSyncCommand($this->context, $this->squalomail, array($this->context->cart->id));
            $command->setMethod(
                $this->getCartExists($this->context->cart->id)
                ? CartSyncCommand::SYNC_METHOD_PATCH
                : CartSyncCommand::SYNC_METHOD_POST
            );
            $command->setSyncMode(CartSyncCommand::SYNC_MODE_REGULAR);
            $command->execute();
        }
    }

    protected function getCartExists($cartId)
    {
        $this->squalomail->get(
            "/ecommerce/stores/{$this->context->shop->id}/carts/{$cartId}",
            array('fields' => array('id'))
        );

        if ($this->squalomail->success()) {
            return true;
        }

        return false;
    }
}
