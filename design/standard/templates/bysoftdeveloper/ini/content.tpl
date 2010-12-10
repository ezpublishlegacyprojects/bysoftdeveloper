<form method="post" action={concat("settings/view/",$current_siteaccess,"/",$ini_file)|ezurl}>



{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">
<h1 class="context-title">Settings for {$ini_file} in siteaccess {$current_siteaccess}</h1>

{* DESIGN: subline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">
<div class="context-attributes">

    <table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
{section var=Blocks loop=$settings}
        <tr>
        <th class="tight">&nbsp;</th>
            <th width="50%">
            {$Blocks.key} ({$Blocks.item.count})&nbsp;&nbsp;&nbsp;
            {if $Blocks.item.editable}
                 <a href={concat( '/settings/edit/', $current_siteaccess, '/', $ini_file, '/', $Blocks.key)|ezurl}>[add setting]</a>
            {/if}
            </th>
        <th class="tight">
            Placement
        </th>
            <th width="50%">
                Value
            </th>
            <th class="tight">&nbsp;</th>
    </tr>
    {section var=Settings loop=$Blocks.item.content sequence=array( 'bgdark', 'bglight' )}
        <tr valign="top" class="{$Settings.sequence}">
            <td width="1">
            {if $Settings.item.removeable}
                <input type="checkbox" name="RemoveSettingsArray[]" value="{$Blocks.key}:{$Settings.key|wash}"/>
            {else}
                <input type="checkbox" disabled="disabled" />
            {/if}
            </td>
            <td width="50%">
                {$Settings.key|wash}
            </td>
            <td width="1">
            {section show=eq( $Settings.item.placement, '' )}
                {section var=Placements loop=$Settings.item.content}
                    {$Placements.item.placement}<br/>
                {/section}
            {section-else}
                {$Settings.item.placement}
            {/section}
            </td>
            <td width="50%">
                {switch match=$Settings.item.type}
                {case match='array'}
                    {section show=ne($Settings.item.placement,'undefined')}
                    {section var=Placements loop=$Settings.item.content}
                        <div class="array">[{$Placements.key}] {$Placements.item.content|wash}</div>
                    {/section}
                    {/section}
                {/case}

                {case in=array( 'enable/disable', 'true/false' )}
                    {if or( eq( $Settings.item.content, 'true' ), eq( $Settings.item.content, 'enabled' ) )}
                        <div class="enabled">{$Settings.item.content}</div>
                    {else}
                        <div class="disabled">{$Settings.item.content}</div>
                    {/if}
                {/case}
                {case match='string'}
                    <div class="string">"{$Settings.item.content|wash}"</div>
                {/case}
                {case match='numeric'}
                    <div class="numeric">{$Settings.item.content|wash}</div>
                {/case}
                {case}
                    <div class="{$Settings.item.type}">{$Settings.item.content|wash}</div>
                {/case}
                {/switch}
            </td>
            <td align="right" width="1">
                {if $Settings.item.editable}
                {switch match=$Settings.item.type}
                    {case match='array'}
                        <a href={concat('settings/edit/', $current_siteaccess, '/', $ini_file, '/', $Blocks.key, '/', $Settings.key, '/', 'siteaccess')|ezurl}>
                        <img src={"edit.gif"|ezimage} alt="{'Edit'|i18n('design/admin/settings')}" /></a>
                    {/case}
                    {case}
                        <a href={concat('settings/edit/', $current_siteaccess, '/', $ini_file, '/', $Blocks.key, '/', $Settings.key, '/', $Settings.item.placement)|ezurl}>
                        <img src={"edit.gif"|ezimage} alt="{'Edit'|i18n('design/admin/settings')}" /></a>
                    {/case}
                {/switch}
                {/if}
            </td>
        </tr>
    {/section}
{/section}
</table>

</div>
{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
<div class="block">
    <input class="button" type="submit" name="RemoveButton" value="Remove selected" />
</div>
{* DESIGN: Control bar END *}</div></div>
</div>

</form>