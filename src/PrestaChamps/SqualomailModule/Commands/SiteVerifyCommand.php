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

/**
 * Verify that the connected sites script has been installed
 *
 * @package PrestaChamps\SqualomailModule\Services
 */
class SiteVerifyCommand extends BaseApiCommand
{
    public $squalomail;
    public $siteId;

    public function __construct(SqualoMail $squalomail, $siteId)
    {
        $this->squalomail = $squalomail;
        $this->siteId = $siteId;
    }

    /**
     * @return bool
     * @throws SqualoMailException
     */
    public function execute()
    {
        $this->squalomail->post("connected-sites/{$this->siteId}/actions/verify-script-installation");

        if ($this->squalomail->success()) {
            return true;
        }

        throw new SqualoMailException($this->squalomail->getLastError());
    }
}
