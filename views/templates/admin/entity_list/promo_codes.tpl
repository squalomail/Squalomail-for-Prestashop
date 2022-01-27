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
<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>{l s='ID' mod='squalomailmodule'}</th>
            <th>{l s='Code' mod='squalomailmodule'}</th>
            <th>{l s='Redemption url' mod='squalomailmodule'}
            <th>{l s='Usage count' mod='squalomailmodule'}
            <th>{l s='Enabled' mod='squalomailmodule'}
            <th>#</th>
        </tr>
        </thead>
        <tbody>
        {foreach $promo_codes as $promo_code}
            <tr>
                <td>{$promo_code.id|escape:'htmlall':'UTF-8'}</td>
                <td>{$promo_code.code|escape:'htmlall':'UTF-8'}</td>
                <td>{$promo_code.redemption_url|escape:'htmlall':'UTF-8'}</td>
                <td>{$promo_code.usage_count|escape:'htmlall':'UTF-8'}</td>
                <td>{$promo_code.enabled|escape:'htmlall':'UTF-8'}</td>
                <td>
                    #
                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>
</div>