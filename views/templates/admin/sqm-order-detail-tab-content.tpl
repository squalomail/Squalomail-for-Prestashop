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
<div class="tab-pane" id="squalomail-order-detail">
    <h4 class="visible-print">{l s='SqualoMail detail' mod='squalomailmodule'}</h4>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>{l s='ID' mod='squalomailmodule'}</th>
                <th>{l s='Customer' mod='squalomailmodule'}</th>
                <th>{l s='Store ID' mod='squalomailmodule'}</th>
                <th>{l s='Financial status' mod='squalomailmodule'}</th>
                <th>{l s='Fulfillment status' mod='squalomailmodule'}</th>
                <th>{l s='Total' mod='squalomailmodule'}</th>
                <th>{l s='Discount' mod='squalomailmodule'}</th>
                <th>{l s='Tax' mod='squalomailmodule'}</th>
                <th>{l s='Shipping' mod='squalomailmodule'}</th>
                <th>{l s='Processed at' mod='squalomailmodule'}</th>
                <th>{l s='Products' mod='squalomailmodule'}</th>
                <th>#</th>
            </tr>
            </thead>
            <tbody>
            {include file='./entity_list/order/line.tpl' order=$order currency_code=$order.currency_code}
            </tbody>
        </table>
    </div>
</div>