{*Table content*}
<div class="block">
<table class="list" cellspacing="1">
	<tr>
		<th>Name</th>
		<th>Identifier</th>
		<th>Object name pattern</th>
		<th>Url alias</th>
		<th>Container</th>
		<th>Sort of Children</th>
		<th>Availablity</th>
	</tr>
	<tr class="bglight">
		<td><input type="text" name="class_name" value="{$class.name}" /></td>
		<td>
			<input type="text" name="class_identifier" value="{$class.identifier}" />
			<input type="hidden" name="class_original_identifier" value="{$class.identifier}" />
		</td>
		<td><input name="class_object_name_pattern" type="text" value="{$class.contentobject_name}" /></td>
		<td><input name="class_url_alias_name" size="5" type="text" value="{$class.url_alias_name}" /></td>
		<td><input name="class_is_container" type="checkbox" {if $class.is_container}checked="checked"{/if} /></td>
		<td>
			{def $sort_fields=fetch( content, available_sort_fields )}
		    <select name="class_default_sorting_field" class="short_param">
		    {foreach $sort_fields as $sf_key => $sf_item}
		        <option value="{$sf_key}" {if eq( $sf_key, $class.sort_field )}selected="selected"{/if}>{$sf_item}</option>
		    {/foreach}
		    </select>
		    <select name="class_default_sorting_order" class="short_param">
		        <option value="0"{if eq($class.sort_order, 0)} selected="selected"{/if}>{'Descending'}</option>
		        <option value="1"{if eq($class.sort_order, 1)} selected="selected"{/if}>{'Ascending'}</option>
		    </select>
		    {undef}
		</td>
		<td>
			<input name="class_always_available" type="checkbox" {if and($class.always_available)}checked="checked"{/if} />
			
			<input type="hidden" name="class_version" value="{$class.version}" />
			<input type="hidden" name="class_id" value="{$class.id}" />
		</td>
	</tr>
</table>

<h2 class="context-title">
	Class Attributes
</h2>

<table id="attributeTable" class="list" cellspacing="1">
<tr>
	<th>Name</th>
	<th>Identifier</th>
	<th>Type</th>
	<th colspan="4">&nbsp;</th>
</tr>
{def $i=0}
{if is_set($attributes)}
	{def $attr_datatype=''}
	{foreach $attributes as $attr}
	{set $attr_datatype=$attr.data_type}
	<tr class="{if eq(mod($i, 2), 0)}bglight{else}bgdark{/if}">
		<td><input type="text" name="attribute_name[{$i}]" value="{$attr.name}" /></td>
		<td><input type="text" name="attribute_identifier[{$i}]" value="{$attr.identifier}" /></td>
		<td>
			<select onchange="javascript:bysoftdeveloperDisabledSelectOnChange(this,'{$attr.data_type_string}');" >
			{foreach $datatypes as $dt}
				<option value="{$dt.information.string}"
					{if eq($attr.data_type_string, $dt.information.string) }
						selected="selected"
					{/if}
				>
				{$dt.information.name|wash}
				</option>
			{/foreach}
			</select>
		</td>
		<td>
			<label class="short_label"><input type="checkbox" name="attribute_is_required[{$i}]" 
				{if eq($attr.is_required, 1)}checked="checked"{/if} />Required
			</label>
		</td>
		<td>
			<label class="short_label"><input type="checkbox" name="attribute_is_searchable[{$i}]" 
			{if eq($attr.is_searchable, 1) }
				checked="checked"
			{elseif not($attr.data_type.is_indexable)}
				disabled="disabled""
			{/if}
			/>Searchable</label>
		</td>
		<td>
			<label class="short_label"><input type="checkbox" name="attribute_is_information_collector[{$i}]"
				{if $attr.is_information_collector}
					checked="checked"
				{elseif not($attr.data_type.is_information_collector)}
					disabled="disabled"
				{/if}
			/>Collector</label>
		</td>
		<td>
			<label class="short_label"><input type="checkbox" name="attribute_can_translate[{$i}]"
				{if eq($attr.can_translate, 1)}
					checked="checked"
				{elseif not($attr.data_type.properties.translation_allowed)}
					disabled="disabled"
				{/if}
			/>Translatable</label>
			<input type="hidden" name="attribute_id[{$i}]" value="{$attr.id}" />
		</td>
	</tr>
	
	{set $i=inc($i)}
	{/foreach}
{/if}
</table>
</div>