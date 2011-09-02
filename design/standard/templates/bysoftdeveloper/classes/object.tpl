{if $contentobject_list}
{def $td_style = 'style="font-weight:bold;"'}
<table class="list">
	<tr>
		<th>Name</th>
		<th>ObjectID</th>
		<th>Locations</th>
		<th>Publish Date</th>
		<th>Modify Date</th>
		<th>Operations</th>
	</tr>
	{foreach $contentobject_list as $object}
	<tr class="bglight" ondblclick="bysoftdeveloperClassesObjectcontentToggle({$object.id})" title="Double click to show the summary">
		<td {$td_style}>{$object.name|wash}</a></td>
		<td {$td_style}>{$object.id|wash}</td>
		<td {$td_style}>
			{foreach $object.assigned_nodes as $node}
			<a href="{$node.url_alias|ezurl(no)}" target="_blank">{$node.url}</a>
				{delimiter} <br/> {/delimiter}
			{/foreach}
		</td>
		<td>{$object.published|datetime('custom', '%Y/%m/%d')}</td>
		<td>{$object.modified|datetime('custom', '%Y/%m/%d')}</td>
		<td>&nbsp;</td>
	</tr>
	<tr class="bglight" style=" background: white;border: 2px dashed orange; display:none;">
		<td id="bysoftdeveloper-classes-objectcontent-{$object.id}" colspan="7" style="display:none;">
		</td>
	</tr>
	{/foreach}
</table>
{/if}