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
            <th>{l s='List ID' mod='squalomailmodule'}</th>
            <th>{l s='Name' mod='squalomailmodule'}</th>
            <th>{l s='Platform' mod='squalomailmodule'}</th>
            <th>{l s='Domain' mod='squalomailmodule'}</th>
            <th>{l s='Is syncing' mod='squalomailmodule'}</th>
            <th>{l s='Is active' mod='squalomailmodule'}</th>
            <th>{l s='Email address' mod='squalomailmodule'}</th>
            <th>{l s='Currency code' mod='squalomailmodule'}</th>
            <th>{l s='Money format' mod='squalomailmodule'}</th>
            <th>{l s='Primary locale' mod='squalomailmodule'}</th>
            <th>{l s='Timezone' mod='squalomailmodule'}</th>
            <th>{l s='Phone' mod='squalomailmodule'}</th>
            <th>{l s='Address' mod='squalomailmodule'}</th>
            <th>{l s='Automations' mod='squalomailmodule'}</th>
            <th>{l s='List is active' mod='squalomailmodule'}</th>
            <th>{l s='Created at' mod='squalomailmodule'}</th>
            <th>{l s='Updated at' mod='squalomailmodule'}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        {foreach $stores as $store}
            <tr>
                <td>{$store.id|escape:'htmlall':'UTF-8'}</td>
                <td>{$store.list_id|escape:'htmlall':'UTF-8'}</td>
                <td>{$store.name|escape:'htmlall':'UTF-8'}</td>
                <td>{$store.platform|escape:'htmlall':'UTF-8'}</td>
                <td>{$store.domain|escape:'htmlall':'UTF-8'}</td>
                <td>{$store.is_syncing|escape:'htmlall':'UTF-8'}</td>
                <td>{$store.list_is_active|escape:'htmlall':'UTF-8'}</td>
                <td>{$store.email_address|escape:'htmlall':'UTF-8'}</td>
                <td>{$store.currency_code|escape:'htmlall':'UTF-8'}</td>
                <td>{$store.money_format|escape:'htmlall':'UTF-8'}</td>
                <td>{$store.primary_locale|escape:'htmlall':'UTF-8'}</td>
                <td>{$store.timezone|escape:'htmlall':'UTF-8'}</td>
                <td>{$store.phone|escape:'htmlall':'UTF-8'}</td>
                <td>{', '|implode:$store.address|escape:'htmlall':'UTF-8'}</td>
                <td>
                    {$JSON_PRETTY_PRINT = 128}
                    {foreach $store.automations as $name => $automation}
                        <div class="well">
                            <p><b>{$name|escape:'htmlall':'UTF-8'}</b></p>
                            {* HTML code, no need for escape *}
                            <pre>{json_encode($automation, $JSON_PRETTY_PRINT)}</pre>
                        </div>
                    {/foreach}
                </td>
                <td>{$store.list_is_active|escape:'htmlall':'UTF-8'}</td>
                <td>{$store.created_at|escape:'htmlall':'UTF-8'}</td>
                <td>{$store.updated_at|escape:'htmlall':'UTF-8'}</td>
                <td>
                    <a href="{LinkHelper::getAdminLink('AdminSqualomailModuleStores', true, [], ['action' => 'entitydelete', 'entity_id' => $store.id])|escape:'htmlall':'UTF-8'}">
                        Delete
                    </a>
                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>
</div>