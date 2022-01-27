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
{$JSON_PRETTY_PRINT = 128}
{*<div class="well">*}
{*<p><b>{$name}</b></p>*}
{*<pre>{json_encode($automation, $JSON_PRETTY_PRINT)}</pre>*}
{*</div>*}
<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>{l s='ID' mod='squalomailmodule'}</th>
            <th>{l s='Created at' mod='squalomailmodule'}</th>
            <th>{l s='Started at' mod='squalomailmodule'}</th>
            <th>{l s='Status' mod='squalomailmodule'}</th>
            <th>{l s='Emails sent' mod='squalomailmodule'}</th>
            <th>{l s='Recipients' mod='squalomailmodule'}</th>
            <th>{l s='Settings' mod='squalomailmodule'}</th>
            <th>{l s='Tracking' mod='squalomailmodule'}</th>
            <th>{l s='Trigger settings' mod='squalomailmodule'}</th>
            <th>{l s='Report summary' mod='squalomailmodule'}</th>
            <th>{l s='' mod='squalomailmodule'}</th>
            <th>{l s='' mod='squalomailmodule'}</th>
            <th>#</th>
        </tr>
        </thead>
        <tbody>
        {foreach $automations as $automation}
            <tr>
                <td>
                    {$automation.id|escape:'htmlall':'UTF-8'}
                </td>
                <td>
                    {$automation.create_time|escape:'htmlall':'UTF-8'}
                </td>
                <td>
                    {$automation.start_time|escape:'htmlall':'UTF-8'}
                </td>
                <td>
                    {$automation.status|escape:'htmlall':'UTF-8'}
                </td>
                <td>
                    {$automation.emails_sent|escape:'htmlall':'UTF-8'}
                </td>
                <td>
                    <div class="well">
                        {* HTML code, no need for escape *}
                        <pre>{json_encode($automation.recipients, $JSON_PRETTY_PRINT)}</pre>
                    </div>
                </td>
                <td>
                    <div class="well">
                        {* HTML code, no need for escape *}
                        <pre>{json_encode($automation.settings, $JSON_PRETTY_PRINT)}</pre>
                    </div>
                </td>
                <td>
                    <div class="well">
                        {* HTML code, no need for escape *}
                        <pre>{json_encode($automation.tracking, $JSON_PRETTY_PRINT)}</pre>
                    </div>
                </td>
                <td>
                    <div class="well">
                        {* HTML code, no need for escape *}
                        <pre>{json_encode($automation.trigger_settings, $JSON_PRETTY_PRINT)}</pre>
                    </div>
                </td>
                <td>
                    <div class="well">
                        {if isset($automation.report_summary)}
                            {* HTML code, no need for escape *}
                            <pre>{json_encode($automation.report_summary, $JSON_PRETTY_PRINT)}</pre>
                        {/if}
                    </div>
                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>
</div>