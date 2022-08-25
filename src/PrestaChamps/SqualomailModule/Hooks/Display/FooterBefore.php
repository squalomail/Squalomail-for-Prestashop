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

namespace PrestaChamps\SqualomailModule\Hooks\Display;

use Context;
use DrewM\SqualoMail\SqualoMail;
use Module;
use PrestaChamps\SqualomailModule\Formatters\ListMemberFormatter;
use Tools;
use UnexpectedValueException;

/**
 * Class FooterBefore
 * @package PrestaChamps\SqualomailModule\Hooks\Display
 */
class FooterBefore
{
    /**
     * @var array
     */
    private $params;

    /**
     * @var SqualoMail
     */
    private $squaloMail;

    /**
     * @var Context
     */
    private $context;

    protected function __construct($params, SqualoMail $squaloMail, Context $context)
    {
        $this->params = $params;
        $this->squaloMail = $squaloMail;
        $this->context = $context;
    }

    public static function run($params, SqualoMail $squalomail, Context $context)
    {
        return new static($params, $squalomail, $context);
    }

    public function newsletterBlockRegistration()
    {
        $subscriptionIsEnabled = Module::isEnabled('Ps_Emailsubscription')
            || Module::isEnabled('blocknewsletter');
        if (Tools::isSubmit('submitNewsletter') && $subscriptionIsEnabled) {
            $subscriberHash = md5(Tools::strtolower(Tools::getValue('email')));
            $listId = $this->getListIdFromStore();
            $this->squaloMail->put(
                "/lists/{$listId}/members/{$subscriberHash}",
                array(
                    'email_address' => Tools::getValue('email'),
                    'status'        => $this->getListRequiresDOI($listId)
                        ? ListMemberFormatter::STATUS_PENDING
                        : ListMemberFormatter::STATUS_SUBSCRIBED
                )
            );
        }
    }

    /**
     * Get list ID from store
     *
     * @return string
     */
    protected function getListIdFromStore()
    {
        $shopId = \Configuration::get(\SqualomailModuleConfig::SQUALOMAIL_API_KEY);
        $listId = $this->squaloMail->get("/ecommerce/stores/{$shopId}", array('fields' => 'list_id'));

        if (isset($listId['list_id']) && $this->squaloMail->success()) {
            return $listId['list_id'];
        }

        throw new UnexpectedValueException("Can't determine LIST id from store");
    }

    /**
     * Decide if a list requires the Double Opt In feature
     *
     * @param $listId
     *
     * @return bool
     */
    protected function getListRequiresDOI($listId)
    {
        $list = $this->squaloMail->get("/lists/{$listId}", array('fields' => 'double_optin'));

        if (isset($list['double_optin']) && $this->squaloMail->success()) {
            return (bool)$list['double_optin'];
        }

        throw new UnexpectedValueException("Can't determine if the value requires double optin or not");
    }
}
