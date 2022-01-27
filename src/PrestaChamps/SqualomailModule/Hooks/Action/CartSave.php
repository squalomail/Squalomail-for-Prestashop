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

namespace PrestaChamps\SqualomailModule\Hooks\Action;

use Context;
use DrewM\SqualoMail\SqualoMail;
use PrestaChamps\SqualomailModule\Commands\CartSyncCommand;

/**
 * Class CartSave
 * @package PrestaChamps\SqualomailModule\Hooks\Action
 */
class CartSave
{
    private $cart;
    private $context;
    /**
     * @var SqualoMail
     */
    private $squalomail;

    private function __construct(Context $context, SqualoMail $squaloMail)
    {
        $this->context = $context;
        $this->squalomail = $squaloMail;
    }

    public static function run(Context $context, SqualoMail $squaloMail)
    {
        (new static($context, $squaloMail))->syncCart();
    }

    protected function syncCart()
    {
        $cartId = isset(Context::getContext()->cart->id) ? Context::getContext()->cart->id : false;
        if ($cartId && !$this->context->customer->isGuest()) {
            $command = new CartSyncCommand($this->context, $this->squalomail, array($cartId));
            $command->setSyncMode(CartSyncCommand::SYNC_MODE_REGULAR);
            $command->setMethod(CartSyncCommand::SYNC_METHOD_POST);
            $command->execute();
            $command->setMethod(CartSyncCommand::SYNC_METHOD_PATCH);
            $command->execute();
        }
    }
}
