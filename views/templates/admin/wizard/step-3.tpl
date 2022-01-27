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
<div class="alert alert-info alert-sqm" id="loading-states-in-progress">
    {l s='Loading statuses, please wait' mod='squalomailmodule'}
</div>
<div id="status-inputs-container" class="hidden">
    <div class="form-group">
        <label class="control-label col-lg-3" for="module-squalomailmoduleconfig-statuses-for-pending">
            {l s='Status for pending' mod='squalomailmodule'}
        </label>

        <div class="col-lg-9">
            <select name="module-squalomailmoduleconfig-statuses-for-pending[]"
                    id="module-squalomailmoduleconfig-statuses-for-pending" multiple="multiple">
            </select>
        </div>
    </div>
    <div class="clearfix"></div>
    <hr>
    <div class="form-group">
        <label class="control-label col-lg-3" for="module-squalomailmoduleconfig-statuses-for-refunded">
            {l s='Status for refunded' mod='squalomailmodule'}
        </label>
        <div class="col-lg-9">
            <select name="module-squalomailmoduleconfig-statuses-for-refunded[]"
                    id="module-squalomailmoduleconfig-statuses-for-refunded" multiple="multiple">
            </select>
        </div>
    </div>
    <div class="clearfix"></div>
    <hr>
    <div class="form-group">
        <label class="control-label col-lg-3" for="module-squalomailmoduleconfig-statuses-for-cancelled">
            {l s='Status for cancelled' mod='squalomailmodule'}
        </label>
        <div class="col-lg-9">
            <select name="module-squalomailmoduleconfig-statuses-for-cancelled[]"
                    id="module-squalomailmoduleconfig-statuses-for-cancelled" multiple="multiple">
            </select>
        </div>
    </div>
    <div class="clearfix"></div>
    <hr>
    <div class="form-group">
        <label class="control-label col-lg-3" for="module-squalomailmoduleconfig-statuses-for-shipped">
            {l s='Status for shipped' mod='squalomailmodule'}
        </label>
        <div class="col-lg-9">
            <select name="module-squalomailmoduleconfig-statuses-for-shipped[]"
                    id="module-squalomailmoduleconfig-statuses-for-shipped" multiple="multiple">
            </select>
        </div>
    </div>
    <div class="clearfix"></div>
    <hr>
    <div class="form-group">
        <label class="control-label col-lg-3" for="module-squalomailmoduleconfig-statuses-for-paid">
            {l s='Status for paid' mod='squalomailmodule'}
        </label>
        <div class="col-lg-9">
            <select name="module-squalomailmoduleconfig-statuses-for-paid[]"
                    id="module-squalomailmoduleconfig-statuses-for-paid" multiple="multiple">
            </select>
        </div>
    </div>
    <div class="clearfix"></div>
    <hr>
</div>
<div class="spinner">
    <div class="double-bounce1"></div>
    <div class="double-bounce2"></div>
</div>