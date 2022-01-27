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
    <div class="alert alert-info alert-sqm" id="categories-sync-in-progress">
        {l s='Syncing categories, please wait' mod='squalomailmodule'}
    </div>
    <div class="alert alert-success alert-sqm hidden" id="categories-sync-completed">
        {l s='The batch operation of syncing the categories has been sent to the Squalomail servers. The setup is now complete' mod='squalomailmodule'}
    </div>
    <div class="alert alert-error hidden" id="categories-sync-error">
        {l s='Error during category sync' mod='squalomailmodule'}
    </div>
    <div class="progress hidden">
        <div class="progress-bar" style="width:0"></div>
    </div>
</div>