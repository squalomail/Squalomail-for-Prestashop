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
use PrestaChamps\SqualomailModule\Exceptions\SqualoMailException;
use PrestaChamps\SqualomailModule\Formatters\OrderFormatter;

/**
 * Class OrderSyncService
 *
 * @package PrestaChamps\SqualomailModule\Services
 */
class OrderSyncCommand extends BaseApiCommand
{
    protected $context;
    protected $orders;
    protected $squalomail;
    protected $batch;
    protected $batchPrefix = '';

    /**
     * ProductSyncService constructor.
     *
     * @param \Context  $context
     * @param SqualoMail $squalomail
     * @param array     $orderIds
     *
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    public function __construct(\Context $context, SqualoMail $squalomail, $orderIds = array())
    {
        $this->context = $context;
        $this->squalomail = $squalomail;
        $this->batchPrefix = uniqid('ORDERS_SYNC', true);
        $this->batch = $this->squalomail->new_batch($this->batchPrefix);
        $this->orders = $orderIds;

        $this->buildOrders();
    }

    public function execute()
    {
        return $this->batch->execute();
    }

    /**
     * @todo Implement batch functionality
     *
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    protected function buildOrders()
    {
        $shopId = \Configuration::get(\SqualomailModuleConfig::SQUALOMAIL_API_KEY);

        foreach ($this->orders as $orderId) {
            $order = new \Order($orderId, $this->context->language->id);
            $shippingAddress = new \Address($order->id_address_delivery, $this->context->language->id);
            $billingAddress = new \Address($order->id_address_invoice, $this->context->language->id);
            try {
                $this->syncCustomer($order->getCustomer());
                $formatter = new OrderFormatter(
                    $order,
                    $order->getCustomer(),
                    $billingAddress,
                    $shippingAddress,
                    $this->context
                );
                if ($this->getOrderExists($orderId)) {
                    $this->squalomail->patch(
                        "/ecommerce/stores/{$shopId}/orders/{$orderId}",
                        $formatter->format()
                    );
                } else {
                    $this->squalomail->post(
                        "/ecommerce/stores/{$shopId}/orders",
                        $formatter->format()
                    );
                }
                if (!$this->squalomail->success()) {
                    throw new SqualoMailException($this->squalomail->getLastResponse());
                }
            } catch (\PrestaShopDatabaseException $exception) {
                \PrestaShopLogger::addLog("[SQUALOMAIL]: {$exception->getMessage()}");
                continue;
            } catch (SqualoMailException $exception) {
                $message = json_encode($exception->getMessage());
                \PrestaShopLogger::addLog("[SQUALOMAIL]: {$message}");
                continue;
            }
        }
    }

    /**
     * @param \Customer $customer
     *
     * @throws \PrestaShopDatabaseException
     * @throws SqualoMailException
     */
    protected function syncCustomer(\Customer $customer)
    {
        if (!$this->getCustomerExists($customer)) {
            $command = new CustomerSyncCommand(
                $this->context,
                $this->squalomail,
                array($customer->id)
            );
            $command->setMethod(CustomerSyncCommand::SYNC_METHOD_PUT);
            $command->triggerDoubleOptIn(true);
            $command->execute();
            if (!$this->squalomail->success()) {
                throw new SqualoMailException($this->squalomail->getLastResponse());
            }
        }
    }

    /**
     * @param \Customer $customer
     *
     * @return bool
     */
    protected function getCustomerExists(\Customer $customer)
    {
        $shopId = \Configuration::get(\SqualomailModuleConfig::SQUALOMAIL_API_KEY);

        $this->squalomail->get(
            "/ecommerce/stores/{$shopId}/customers/{$customer->id}",
            array('fields' => array('opt_in_status'))
        );

        if ($this->squalomail->success()) {
            return true;
        }

        return false;
    }

    protected function getOrderExists($orderId)
    {
        $shopId = \Configuration::get(\SqualomailModuleConfig::SQUALOMAIL_API_KEY);

        $this->squalomail->get(
            "/ecommerce/stores/{$shopId}/orders/{$orderId}",
            array('fields' => array('id'))
        );

        if ($this->squalomail->success()) {
            return true;
        }

        return false;
    }
}
