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
 * @copyright Squalomail
 * @license   commercial
 */

namespace PrestaChamps\SqualomailModule\Commands;

use Context;
use Customer;
use CustomerCore;
use DrewM\SqualoMail\SqualoMail;
use PrestaChamps\SqualomailModule\Formatters\CustomerFormatter;
use PrestaChamps\SqualomailModule\Formatters\ListMemberFormatter;
use PrestaShopDatabaseException;
use Tools;

/**
 * Class CustomerSyncCommand
 *
 * @package PrestaChamps\SqualomailModule\Commands
 */
class CustomerSyncCommand extends BaseApiCommand
{
    protected $context;
    protected $customerIds;
    protected $squalomail;
    protected $batch;
    protected $batchPrefix = '';
    protected $triggerDoubleOptIn = true;

    /**
     * ProductSyncService constructor.
     *
     * @param Context $context
     * @param SqualoMail $squalomail
     * @param array $customerIds
     */
    public function __construct(Context $context, SqualoMail $squalomail, $customerIds = array())
    {
        $this->context = $context;
        $this->squalomail = $squalomail;
        $this->batchPrefix = uniqid('CUSTOMER_SYNC', true);
        $this->batch = $this->squalomail->new_batch($this->batchPrefix);
        $this->customerIds = $customerIds;
    }

    /**
     * Trigger DoubleOptIn feature
     *
     * @param bool $trigger
     */
    public function triggerDoubleOptIn($trigger = true)
    {
        $this->triggerDoubleOptIn = (bool)$trigger;
    }

    /**
     * @return array
     * @throws PrestaShopDatabaseException
     */
    public function execute()
    {
        $shopId = \Configuration::get(\SqualomailModuleConfig::SQUALOMAIL_API_KEY);

        $this->responses = array();
        if ((int)$this->syncMode === self::SYNC_MODE_REGULAR) {
            $listId = $this->getListIdFromStore();
            $listRequiresDoi = $this->getListRequiresDOI($listId);
            foreach ($this->customerIds as $customerId) {
                $customer = new Customer($customerId);
                $formatted = new CustomerFormatter($customer, $this->context);
                if ($this->method === self::SYNC_METHOD_POST || $this->method === self::SYNC_METHOD_PUT) {
                    $data = $formatted->format();
                    /**
                     * @var $customer CustomerCore
                     */
                    $listMemberFormatter = new ListMemberFormatter(
                        $customer,
                        $this->context,
                        $this->getMemberNewsletterStatus($customer, $listRequiresDoi),
                        ListMemberFormatter::EMAIL_TYPE_HTML
                    );

                    $data['opt_in_status'] = ($customer->newsletter == '1') ? true : false;
                    $this->squalomail->put(
                        "/ecommerce/stores/{$shopId}/customers/{$customerId}",
                        $data
                    );
                    $hash = md5(Tools::strtolower($customer->email));
                    $this->squalomail->put("/lists/{$listId}/members/{$hash}", $listMemberFormatter->format());
                }
                if ($this->method === self::SYNC_METHOD_PATCH) {
                    $data = $formatted->format();
                    $this->squalomail->put(
                        "/ecommerce/stores/{$shopId}/customers/{$customerId}",
                        $data
                    );
                }
                if ($this->method === self::SYNC_METHOD_DELETE) {
                    $this->squalomail->delete(
                        "/ecommerce/stores/{$shopId}/customers/{$customerId}"
                    );
                }
                $this->responses[] = $this->squalomail->getLastResponse();
            }
        }

        if ((int)$this->syncMode === self::SYNC_MODE_BATCH) {
            $batch = $this->squalomail->new_batch();
            foreach ($this->customerIds as $customerId) {
                $formatted = new CustomerFormatter(new Customer($customerId), $this->context);
                if ($this->method === 'POST') {
                    $batch->put(
                        "{$this->batchPrefix}_{$customerId}",
                        "/ecommerce/stores/{$shopId}/customers/{$customerId}",
                        $formatted->format()
                    );
                }
                if ($this->method === 'PATCH') {
                    $data = $formatted->format();
                    $batch->put(
                        "{$this->batchPrefix}_{$customerId}",
                        "/ecommerce/stores/{$shopId}/customers/{$customerId}",
                        $data
                    );
                }
                if ($this->method === 'DELETE') {
                    $batch->delete(
                        "{$this->batchPrefix}_{$customerId}",
                        "/ecommerce/stores/{$shopId}/customers/{$customerId}"
                    );
                }
                $this->responses[] = $this->squalomail->getLastResponse();
            }
            $this->responses[] = $batch->execute();
        }

        return $this->responses;
    }

    /**
     * @param Customer $customer
     * @param bool $listRequiresDoi
     * @return string
     */
    public function getMemberNewsletterStatus(Customer $customer, $listRequiresDoi)
    {
        if (!$customer->newsletter) {
            return ListMemberFormatter::STATUS_TRANSACTIONAL;
        }
        if ($listRequiresDoi && $customer->newsletter) {
            return ListMemberFormatter::STATUS_PENDING;
        }

        if (!$listRequiresDoi && $customer->newsletter) {
            return ListMemberFormatter::STATUS_SUBSCRIBED;
        }

        return ListMemberFormatter::STATUS_TRANSACTIONAL;
    }
}
