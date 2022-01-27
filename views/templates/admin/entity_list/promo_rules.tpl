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
            <th>{l s='Title' mod='squalomailmodule'}</th>
            <th>{l s='Starts at' mod='squalomailmodule'}
            <th>{l s='Ends at' mod='squalomailmodule'}
            <th>{l s='Amount' mod='squalomailmodule'}
            <th>{l s='type' mod='squalomailmodule'}
            <th>{l s='target' mod='squalomailmodule'}
            <th>{l s='enabled' mod='squalomailmodule'}
            <th>#</th>
        </tr>
        </thead>
        <tbody>
        {foreach $promo_rules as $promo_rule}
            <tr>
                <td>{$promo_rule.id|escape:'htmlall':'UTF-8'}</td>
                <td>{$promo_rule.title|escape:'htmlall':'UTF-8'}</td>
                <td>{$promo_rule.starts_at|escape:'htmlall':'UTF-8'}</td>
                <td>{$promo_rule.ends_at|escape:'htmlall':'UTF-8'}</td>
                <td>{$promo_rule.amount|escape:'htmlall':'UTF-8'}</td>
                <td>{$promo_rule.type|escape:'htmlall':'UTF-8'}</td>
                <td>{$promo_rule.target|escape:'htmlall':'UTF-8'}</td>
                <td>{$promo_rule.enabled|escape:'htmlall':'UTF-8'}</td>
                <td>
                    <div class="btn-group  btn-group-xs" role="group" aria-label="...">
                        <a class="btn btn-default"
                           href="{LinkHelper::getAdminLink('AdminSqualomailModulePromoCodes', true, [], ['action' => 'entitydelete', 'entity_id' => $promo_rule.id])|escape:'htmlall':'UTF-8'}">
                            Delete
                        </a>

                        <a class="btn btn-default"
                           href="{LinkHelper::getAdminLink('AdminSqualomailModulePromoCodes', true, [], ['rule_id' => $promo_rule.id])|escape:'htmlall':'UTF-8'}">
                            {l s='Promo codes' mod='squalomailmodule'}
                        </a>
                    </div>

                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>
</div>