<div id="case-caselink-cases" class="crm-accordion-wrapper collapsed">

    <div class="crm-accordion-header">{ts}Related sub cases{/ts}</div>

    <div class="crm-accordion-body">
        {if $permission EQ 'edit'}
            <div class="action-link">
                {capture assign=newCaseUrl}{crmURL p="civicrm/case/add" q="reset=1&action=add&context=standalone&caselink_case_id=`$caseId`"}{/capture}
                <a accesskey="N" href="{$newCaseUrl}" class="button">
                    <span><div class="icon add-icon"></div>{ts}New related case{/ts}</span>
                </a>
            </div>
        {/if}
        <table>
            <thead>
            <tr>
                <th class="ui-state-default">{ts}Client{/ts}</th>
                <th class="ui-state-default">{ts}Case type{/ts}</th>
                <th class="ui-state-default">{ts}Status{/ts}</th>
                <th class="ui-state-default">{ts}Subject{/ts}</th>
                <th class="no-sort ui-state-default"></th>
            </tr>
            </thead>
            <tbody>

            {foreach from=$cases item=case}
                <tr class="{cycle values="odd,even"}">
                    <td>{$case.display_name}</td>
                    <td>{$case.case_type}</td>
                    <td>{$case.status}</td>
                    <td>{$case.subject}</td>
                    <td>
                        <a href="{crmURL p="civicrm/contact/view/case" q="action=view&reset=1&id=`$case.case_id`&cid=`$case.client_id`&context=case"}">{ts}Manage case{/ts}
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
        var caselinkCases = cj('#case-caselink-cases').detach();
        cj('#{/literal}{$customGroupName}{literal}').after(caselinkCases);
    });
    {/literal}
</script>