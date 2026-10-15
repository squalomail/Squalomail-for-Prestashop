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
    /**
     * @var string SqualoMail connected site id (site_foreign_id)
     */
    public $siteId;

    /**
     * @param SqualoMail $squalomail
     * @param string     $siteId
     */
    public function __construct(SqualoMail $squalomail, $siteId)
    {
        $this->squalomail = $squalomail;
        $this->siteId = $siteId;
    }

    /**
     * Ask SqualoMail to re-check the tracking script of the connected site
     *
     * Callers are expected to run this only when the connected site changes —
     * on module activation and after a store sync — never per request.
     *
     * @return bool False when there is nothing to verify, true on success
     * @throws SqualoMailException
     */
    public function execute()
    {

        // Without an id the request can only 404, so skip the round trip
        if (empty($this->siteId)) {
            return false;
        }

        $this->squalomail->post("connected-sites/{$this->siteId}/actions/verify-script-installation");

        if ($this->squalomail->success()) {
            return true;
        }

        throw new SqualoMailException($this->squalomail->getLastError());
    }
}
