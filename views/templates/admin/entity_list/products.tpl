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
            <th></th>
            <th>{l s='Name' mod='squalomailmodule'}</th>
            <th>{l s='Description' mod='squalomailmodule'}</th>
            <th>{l s='Type' mod='squalomailmodule'}</th>
            <th>{l s='Vendor' mod='squalomailmodule'}</th>
            <th>{l s='Variants' mod='squalomailmodule'}</th>
            <th>#</th>
        </tr>
        </thead>
        <tbody>
        {foreach $products as $product}
            <tr>
                <td>{$product.id|escape:'htmlall':'UTF-8'}</td>
                <td>
                    <img src="{$product.image_url|escape:'htmlall':'UTF-8'}" class="img-responsive" style="max-width: 75px">
                </td>
                <td>
                    <a href="{$product.url|escape:'htmlall':'UTF-8'}">
                        {$product.title|escape:'htmlall':'UTF-8'}
                    </a>
                </td>
                <td>{$product.description|escape:'htmlall':'UTF-8'}</td>
                <td>{$product.type|escape:'htmlall':'UTF-8'}</td>
                <td>{$product.vendor|escape:'htmlall':'UTF-8'}</td>
                <td>
                    {include file='./product/variants.tpl' variants=$product.variants}
                </td>
                <td>
                    <a href="{LinkHelper::getAdminLink('AdminSqualomailModuleProducts', true, [], ['action' => 'entitydelete', 'entity_id' => $product.id])|escape:'htmlall':'UTF-8'}">
                        Delete
                    </a>
                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>
</div>