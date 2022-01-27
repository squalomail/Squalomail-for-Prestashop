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
    <div class="alert alert-info alert-sqm" id="shop-sync-in-progress">
        {l s='Syncing shops, please wait' mod='squalomailmodule'}
    </div>
    <div class="alert alert-success alert-sqm hidden" id="shop-sync-completed">
        {l s='Syncing shops completed' mod='squalomailmodule'}
    </div>

    <div class="alert alert-error hidden" id="shop-sync-error">
        {l s='Error during shop sync error' mod='squalomailmodule'}
    </div>
    <div class="spinner">
        <div class="double-bounce1"></div>
        <div class="double-bounce2"></div>
    </div>
</div>