{*
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
 *}
<div class="text-center">
    <div class="alert alert-info alert-sqm" id="promo-code-sync-in-progress">
        {l s='Syncing promo-codes, please wait' mod='squalomailmodule'}
    </div>
    <div class="alert alert-success alert-sqm hidden" id="promo-code-sync-completed">
        {l s='The batch operation of syncing the promo-codes has been sent to the Squalomail servers.' mod='squalomailmodule'}
    </div>
    <div class="alert alert-error hidden" id="promo-code-sync-error">
        {l s='Error during promo-code sync' mod='squalomailmodule'}
    </div>
    <div class="progress hidden">
        <div class="progress-bar" style="width:0"></div>
    </div>
</div>