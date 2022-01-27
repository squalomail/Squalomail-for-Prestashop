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

namespace PrestaChamps\SqualomailModule\Formatters;

class OrderStateFormatter
{
    protected $context;
    protected $orderState;

    /**
     * OrderStatusFormatter constructor.
     *
     * @param \OrderState $orderState
     * @param \Context    $context
     */
    public function __construct(\OrderState $orderState, \Context $context)
    {
        $this->orderState = $orderState;
        $this->context = $context;
    }

    /**
     * @return string
     * @throws \PrestaShopException
     */
    public function format()
    {
        $statuses = \Configuration::getMultiple(
            array(
                \SqualomailModuleConfig::STATUSES_FOR_PAID,
                \SqualomailModuleConfig::STATUSES_FOR_CANCELLED,
                \SqualomailModuleConfig::STATUSES_FOR_REFUNDED,
                \SqualomailModuleConfig::STATUSES_FOR_PENDING,
            ),
            $this->context->language->id,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );

        $paid = isset($statuses[\SqualomailModuleConfig::STATUSES_FOR_PAID]) ?
            json_decode($statuses[\SqualomailModuleConfig::STATUSES_FOR_PAID]) : array();
        $cancelled = isset($statuses[\SqualomailModuleConfig::STATUSES_FOR_CANCELLED]) ?
            json_decode($statuses[\SqualomailModuleConfig::STATUSES_FOR_CANCELLED]) : array();
        $refunded = isset($statuses[\SqualomailModuleConfig::STATUSES_FOR_REFUNDED]) ?
            json_decode($statuses[\SqualomailModuleConfig::STATUSES_FOR_REFUNDED]) : array();
        $pending = isset($statuses[\SqualomailModuleConfig::STATUSES_FOR_PENDING]) ?
            json_decode($statuses[\SqualomailModuleConfig::STATUSES_FOR_PENDING]) : array();

        if ($this->orderState->paid || in_array($this->orderState->id, $paid, false)) {
            return 'paid';
        }
        if (in_array($this->orderState->id, $cancelled, false)) {
            return 'cancelled';
        }
        if (in_array($this->orderState->id, $refunded, false)) {
            return 'refunded';
        }
        if (in_array($this->orderState->id, $pending, false)) {
            return 'pending';
        }

        return false;
    }
}
