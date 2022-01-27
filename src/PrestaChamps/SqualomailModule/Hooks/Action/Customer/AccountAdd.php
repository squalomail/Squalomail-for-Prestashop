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
 * Invoked when a new customer creates an account successfully
 *
 * @package PrestaChamps\SqualomailModule\Hooks\Action\Customer
 */
class AccountAdd
{
    protected $context;
    protected $customer;
    protected $squalomail;

    /**
     * AccountAdd constructor
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

        $this->handleGuestCheckoutAbandonedMail();
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
        $command->triggerDoubleOptIn(true);
        $command->setMethod(CustomerSyncCommand::SYNC_METHOD_POST);
        $command->setSyncMode(CustomerSyncCommand::SYNC_MODE_REGULAR);
        $command->execute();
    }

    protected function syncCart()
    {
        if ($this->context->cart) {
            $command = new CartSyncCommand($this->context, $this->squalomail, array($this->context->cart->id));
            $command->setMethod(CartSyncCommand::SYNC_METHOD_POST);
            $command->setSyncMode(CartSyncCommand::SYNC_MODE_REGULAR);
            $command->execute();
        }
    }
}
