{def $data_map = $object.data_map}
<div style="font-size:200%; font-weight:bold; color: green;">Summary:</div>
<table class="list"  title="Summary of {$object.name|wash}">
	<tr ondblclick="javascript:bysoftdeveloperToggleshow(this);">
		<th>Name</th>
		<th>Identifier</th>
		<th>Value</th>
	</tr>
{foreach $data_map as $attribute}
	<tr class="bglight">
		<td style="font-weight:bold;">{$attribute.contentclass_attribute.name}	</td>
		<td style="font-style:italic;">{$attribute.contentclass_attribute.identifier}</td>
		<td>{if $attribute.has_content}{attribute_view_gui attribute=$attribute}{else}[N/A]{/if}</td>
	</tr>
{/foreach}
</table>