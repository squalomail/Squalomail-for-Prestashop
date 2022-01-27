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
            <th>{l s='Handle' mod='squalomailmodule'}</th>
            <th>{l s='Nr. of products' mod='squalomailmodule'}</th>
            <th>#</th>
        </tr>
        </thead>
        <tbody>
        {foreach $categories as $category}
            <tr>
                <td>{$category.id}</td>
                <td>{$category.title}</td>
                <td>{$category.handle}</td>
                <td>{$category.products|@count}</td>
                <td>
                    <a href="{LinkHelper::getAdminLink('AdminSqualomailModuleCategories', true, [], ['action' => 'entitydelete', 'entity_id' => $category.id])|escape:'htmlall':'UTF-8'}">
                        Delete
                    </a>
                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>
</div>