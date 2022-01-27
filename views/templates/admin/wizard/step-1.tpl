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
    <label for="api-key" class="hidden">{l s='API key' mod='squalomailmodule'}</label>
    <p id="logged-in-as-container" {if empty($apiKey) && empty($sqmEmail)}class="hidden"{/if}>
        {l s='Logged in as:' mod='squalomailmodule'} <b id="logged-in-as">{$sqmEmail}</b>
    </p>
    <input type="text" class="form-control" name="api-key" id="api-key" style="max-width: 300px;"
           placeholder="{l s='API key' mod='squalomailmodule'}" required="" value="{$apiKey}">
    <a class="btn btn-default" id="oauth2-start">
        {if empty($apiKey) && empty($sqmEmail)}
            Log in to Squalomail
        {else}
            Log in as somebody else
        {/if}
    </a>
</div>