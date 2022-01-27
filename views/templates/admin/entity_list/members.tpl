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
            <th>{l s='Email' mod='squalomailmodule'}</th>
            <th>{l s='Email type' mod='squalomailmodule'}</th>
            <th>{l s='Status' mod='squalomailmodule'}</th>
            <th>{l s='IP signup' mod='squalomailmodule'}</th>
            <th>{l s='Signup time' mod='squalomailmodule'}</th>
            <th>{l s='IP Opt-in' mod='squalomailmodule'}</th>
            <th>{l s='Language' mod='squalomailmodule'}</th>
            <th>{l s='VIP' mod='squalomailmodule'}</th>
        </tr>
        </thead>
        <tbody>
        {foreach $members as $member}
            <tr>

                <td>{$member.id|escape:'htmlall':'UTF-8'}</td>
                <td>{$member.email_address|escape:'htmlall':'UTF-8'}</td>
                <td>{$member.email_type|escape:'htmlall':'UTF-8'}</td>
                <td>{$member.status|escape:'htmlall':'UTF-8'}</td>
                <td>{$member.ip_signup|escape:'htmlall':'UTF-8'}</td>
                <td>{$member.timestamp_signup|escape:'htmlall':'UTF-8'}</td>
                <td>{$member.ip_opt|escape:'htmlall':'UTF-8'}</td>
                <td>{$member.language|escape:'htmlall':'UTF-8'}</td>
                <td>{$member.vip|escape:'htmlall':'UTF-8'}</td>
            </tr>
        {/foreach}
        </tbody>
    </table>
</div>