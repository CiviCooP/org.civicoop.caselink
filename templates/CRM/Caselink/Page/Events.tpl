<div id="case-caselink-events" class="crm-accordion-wrapper">

    <div class="crm-accordion-header">{ts}Related events{/ts}</div>

    <div class="crm-accordion-body">
        {if $permission EQ 'edit'}
            <div class="action-link">
                {capture assign=newEventURL}{crmURL p="civicrm/event/add" q="reset=1&action=add&caselink_case_id=`$caseId`"}{/capture}
                <a accesskey="N" href="{$newEventURL}" class="button">
                    <span><div class="icon add-icon"></div>{ts}New related event{/ts}</span>
                </a>
            </div>
        {/if}
        <table>
            <thead>
            <tr>
                <th class="ui-state-default">{ts}Start date{/ts}</th>
                <th class="ui-state-default">{ts}Event type{/ts}</th>
                <th class="ui-state-default">{ts}Title{/ts}</th>
                <th class="no-sort ui-state-default"></th>
            </tr>
            </thead>
            <tbody>

            {foreach from=$events item=event}
                <tr class="{cycle values="odd,even"}">
                    <td>{$event.start_date|crmDate:"%b %d, %Y %l:%M %P"}</td>
                    <td>{$event.event_type}</td>
                    <td>{$event.title}</td>
                    <td>
                        <a href="{crmURL p="civicrm/event/manage/settings" q="action=update&reset=1&id=`$event.event_id`"}">{ts}Configure event{/ts}
                    </td>
                </tr>
            {/foreach}
            </tbody>
        </table>

    </div>
</div>


<script type="text/javascript">
    {literal}
    cj(function() {
        var caselinkEvents = cj('#case-caselink-events').detach();
        cj('#{/literal}{$customGroupName}{literal}').after(caselinkEvents);
    });
    {/literal}
</script>
