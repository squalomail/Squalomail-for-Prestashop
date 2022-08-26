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

use DrewM\SqualoMail\SqualoMail;
use PrestaChamps\SqualomailModule\Exceptions\SqualoMailException;

/**
 * Class BaseApiCommand
 *
 * @package PrestaChamps\SqualomailModule\Commands
 */
abstract class BaseApiCommand
{
    abstract public function execute();

    protected $method = 'POST';

    protected $syncMode = self::SYNC_MODE_REGULAR;

    /**
     * @var $squalomail SqualoMail
     */
    protected $squalomail;

    /**
     * @var $context \Context
     */
    protected $context;

    const SYNC_MODE_BATCH   = 1;
    const SYNC_MODE_REGULAR = 0;

    const SYNC_METHOD_POST   = 'POST';
    const SYNC_METHOD_PATCH  = 'PATCH';
    const SYNC_METHOD_DELETE = 'DELETE';
    const SYNC_METHOD_PUT    = 'PUT';

    const SUPPORTED_METHODS = array(
        self::SYNC_METHOD_POST,
        self::SYNC_METHOD_PATCH,
        self::SYNC_METHOD_DELETE,
        self::SYNC_METHOD_PUT
    );

    protected $responses = array();

    /**
     * Set the method based on object create, update, etc
     *
     * @param $method
     *
     * @throws SqualoMailException
     */
    public function setMethod($method)
    {
        if (!in_array($method, self::SUPPORTED_METHODS, false)) {
            throw new SqualoMailException('Unsupported method');
        }
        $this->method = $method;
    }

    /**
     * Set sync mode Regular or Batch
     *
     * @param $mode
     *
     * @throws \Exception
     */
    public function setSyncMode($mode)
    {
        if (in_array($mode, array(self::SYNC_MODE_REGULAR, self::SYNC_MODE_BATCH), true)) {
            $this->syncMode = $mode;
        } else {
            throw new \Exception('Unknow mode');
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
        $listId = $this->squalomail->get("/ecommerce/stores/{$shopId}", array('fields' => 'list_id'));

        if (isset($listId['list_id']) && $this->squalomail->success()) {
            return $listId['list_id'];
        }

        throw new \UnexpectedValueException("Can't determine LIST id from store");
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
        $list = $this->squalomail->get("/lists/{$listId}", array('fields' => 'double_optin'));

        if (isset($list['double_optin']) && $this->squalomail->success()) {
            return (bool)$list['double_optin'];
        }

        throw new \UnexpectedValueException("Can't determine if the value requires double optin or not");
    }
}
