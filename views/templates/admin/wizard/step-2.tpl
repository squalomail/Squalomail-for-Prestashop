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
<div class="form-group">
    <div class="alert alert-info alert-sqm" id="loading-lists-in-progress">
        {l s='Loading lists, please wait' mod='squalomailmodule'}
    </div>
    <div id="input-container" class="hidden">
        <label for="list-select">{l s='Select list' mod='squalomailmodule'}</label>
        <select class="form-control" id="list-select">
        </select>
    </div>
    <div class="spinner">
        <div class="double-bounce1"></div>
        <div class="double-bounce2"></div>
    </div>
</div>