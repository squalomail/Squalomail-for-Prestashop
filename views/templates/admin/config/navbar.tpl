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
<div class="row">
    <div class="col-sm-6 hidden-xs">
        <img class="img-responsive" src="../modules/squalomailmodule/views/img/logo-horizontal.png" height="326" width="1200" style="max-height: 32px; width: auto">
    </div>
    <div class="col-sm-6">
        <div class="btn-group pull-right" role="group" style="height:100%; vertical-align:center;line-height : 100%;">
            <a class="btn btn-default" href="{LinkHelper::getAdminLink('AdminSqualomailModuleWizard')|escape:'htmlall':'UTF-8'}">
                <i class="icon icon-floppy-o" aria-hidden="true"></i>

                {l s='Setup wizard' mod='squalomailmodule'}
            </a>
            <a class="btn btn-default hidden" href="{LinkHelper::getAdminLink('AdminSqualomailModuleSync')|escape:'htmlall':'UTF-8'}">
                <i class="icon icon-retweet" aria-hidden="true"></i>
                {l s='Sync' mod='squalomailmodule'}
            </a>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-primary btn-sqm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                    <i class="icon icon-folder-open-o" aria-hidden="true"></i>
                    {l s='Squalomail Objects' mod='squalomailmodule'}
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a href="{LinkHelper::getAdminLink('AdminSqualomailModuleBatches')|escape:'htmlall':'UTF-8'}">
                            {l s='Batches' mod='squalomailmodule'}
                        </a>
                    </li>
                    <li>
                        <a href="{LinkHelper::getAdminLink('AdminSqualomailModuleCarts')|escape:'htmlall':'UTF-8'}">
                            {l s='Carts' mod='squalomailmodule'}
                        </a>
                    </li>
                    <li>
                        <a href="{LinkHelper::getAdminLink('AdminSqualomailModuleCustomers')|escape:'htmlall':'UTF-8'}">
                            {l s='Customers' mod='squalomailmodule'}
                        </a>
                    </li>
                    <li>
                        <a href="{LinkHelper::getAdminLink('AdminSqualomailModuleLists')|escape:'htmlall':'UTF-8'}">
                            {l s='Lists' mod='squalomailmodule'}
                        </a>
                    </li>
                    <li>
                        <a href="{LinkHelper::getAdminLink('AdminSqualomailModuleOrders')|escape:'htmlall':'UTF-8'}">
                            {l s='Orders' mod='squalomailmodule'}
                        </a>
                    </li>
                    <li>
                        <a href="{LinkHelper::getAdminLink('AdminSqualomailModuleProducts')|escape:'htmlall':'UTF-8'}">
                            {l s='Products' mod='squalomailmodule'}
                        </a>
                    </li>
                    <li>
                        <a href="{LinkHelper::getAdminLink('AdminSqualomailModuleStores')|escape:'htmlall':'UTF-8'}">
                            {l s='Stores' mod='squalomailmodule'}
                        </a>
                    </li>

                    <li>
                        <a href="{LinkHelper::getAdminLink('AdminSqualomailModuleSites')|escape:'htmlall':'UTF-8'}">
                            {l s='Sites' mod='squalomailmodule'}
                        </a>
                    </li>
                    <li>
                        <a href="{LinkHelper::getAdminLink('AdminSqualomailModuleAutomations')|escape:'htmlall':'UTF-8'}">
                            {l s='Automations' mod='squalomailmodule'}
                        </a>
                    </li>
                    <li>
                        <a href="{LinkHelper::getAdminLink('AdminSqualomailModulePromoRules')|escape:'htmlall':'UTF-8'}">
                            {l s='Promo rules' mod='squalomailmodule'}
                        </a>
                    </li>
                    <li>
                        <a href="{LinkHelper::getAdminLink('AdminSqualomailModuleCategories')|escape:'htmlall':'UTF-8'}">
                            {l s='Categories' mod='squalomailmodule'}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<hr>