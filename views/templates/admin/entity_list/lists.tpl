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
            <th>{l s='Web ID' mod='squalomailmodule'}</th>
            <th>{l s='Name' mod='squalomailmodule'}
            <th>#</th>
        </tr>
        </thead>
        <tbody>
        {foreach $lists as $list}
            <tr>

                <td>{$list.id|escape:'htmlall':'UTF-8'}</td>
                <td>{$list.web_id|escape:'htmlall':'UTF-8'}</td>
                <td>{$list.name|escape:'htmlall':'UTF-8'}</td>
                <td>
                    <div class="btn-group  btn-group-xs" role="group" aria-label="...">
                        <a class="btn btn-default"
                           href="{LinkHelper::getAdminLink('AdminSqualomailModuleLists', true, [], ['action' => 'entitydelete', 'entity_id' => $list.id])|escape:'htmlall':'UTF-8'}">
                            Delete
                        </a>

                        <a class="btn btn-default"
                           href="{LinkHelper::getAdminLink('AdminSqualomailModuleListMembers', true, [], ['list_id' => $list.id])|escape:'htmlall':'UTF-8'}">
                            {l s='Members' mod='squalomailmodule'}
                        </a>
                    </div>

                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>
</div>
<button type="button" class="btn btn-success" data-toggle="modal" data-target="#new-list-modal" style="display: none">
    {l s='Add new list' mod='squalomailmodule'}
</button>
<div class="modal fade" id="new-list-modal" tabindex="-1" role="dialog" aria-labelledby="new-list-modal-label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form class="form-horizontal" role="form"
                  action="{LinkHelper::getAdminLink ('AdminSqualomailModuleLists', false, [], ['action' => 'new'])|escape:'htmlall':'UTF-8'}"
                  method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="new-list-modal-label">
                        {l s='Add new list' mod='squalomailmodule'}
                    </h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" value="{getAdminToken tab='AdminSqualomailModuleLists'}" name="token">
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="list-name-input">
                            {l s='List name' mod='squalomailmodule'}
                        </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="list-name-input"
                                   placeholder="{l s='List name' mod='squalomailmodule'}" name="list_name"/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        {l s='Cancel' mod='squalomailmodule'}
                    </button>
                    <button type="submit" class="btn btn-primary">{l s='Save' mod='squalomailmodule'}</button>
                </div>
            </form>
        </div>
    </div>
</div>