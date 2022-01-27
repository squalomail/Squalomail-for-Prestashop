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
<div class="panel">
    <h3>SqualoMail Cart info</h3>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>{l s='ID' mod='squalomailmodule'}</th>
            <th>{l s='Customer' mod='squalomailmodule'}</th>
            <th>{l s='Order total' mod='squalomailmodule'}</th>
            <th>{l s='Products' mod='squalomailmodule'}</th>
            <th>{l s='Created at' mod='squalomailmodule'}</th>
            <th>{l s='Updated at' mod='squalomailmodule'}</th>
        </tr>
        </thead>
        <tbody>
            <tr>
                <td>{$cart.id|escape:'htmlall':'UTF-8'}</td>
                <td>{$cart.customer.email_address|escape:'htmlall':'UTF-8'}</td>
                <td>{$cart.order_total|escape:'htmlall':'UTF-8'} {$cart.currency_code|escape:'htmlall':'UTF-8'}</td>
                <td>
                    {include file='./entity_list/cart/line.tpl' lines=$cart.lines currency_code=$cart.currency_code}
                </td>
                <td>{$cart.created_at|escape:'htmlall':'UTF-8'}</td>
                <td>{$cart.updated_at|escape:'htmlall':'UTF-8'}</td>
            </tr>
        </tbody>
    </table>
</div>