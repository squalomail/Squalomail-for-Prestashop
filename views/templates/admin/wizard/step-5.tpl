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
    <div class="alert alert-info alert-sqm" id="product-sync-in-progress">
        {l s='Syncing products, please wait' mod='squalomailmodule'}
    </div>
    <div class="alert alert-success alert-sqm hidden" id="product-sync-completed">
        {l s='The batch operation of syncing the products has been sent to the Squalomail servers.' mod='squalomailmodule'}
    </div>
    <div class="alert alert-error hidden" id="product-sync-error">
        {l s='Error during product sync error' mod='squalomailmodule'}
    </div>
    <div class="progress hidden">
        <div class="progress-bar" style="width:0"></div>
    </div>
</div>