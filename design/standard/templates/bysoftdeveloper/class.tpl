<script type="text/javascript">
var datatypeInfoArray = '{$datatypeInfoArray}';
datatypeInfoArray = eval("(" + datatypeInfoArray + ");" );
var easypublishClassBaseUrl = {'bysoftdeveloper/class'|ezurl()};
</script>
<script type="text/javascript" src={'javascript/bysoftdeveloper/easypublishclass.js'|ezdesign}></script>
{literal}
<style type="text/css">
label{
	font-weight: normal;
}
.short_param{
	width: 5.5em;
}
.short_type{
	width: 10em;
}
.short_label{
	font-size: 0.8em;
}
</style>
{/literal}
<form action={if is_set($class)}{concat('bysoftdeveloper/class/', $class.identifier)|ezurl()}{else}{'bysoftdeveloper/class'|ezurl()}{/if} onsubmit="return(easypublishClassCheckForm());" method="post">
<!-- begin class information box -->
<div class="context-block">

{*Attribute Header Info*}
<div class="box-header">
<div class="box-tc">
<div class="box-ml">
<div class="box-mr">
<div class="box-tl">
<div class="box-tr">
	<div class="context-title">
		{if is_set($class)}
			<strong>
				<a href={concat( "/class/view/", $class.id )|ezurl} title="{concat('View the <', $class.name, '> class.')|wash()}">{$class.name}</a>
				&nbsp;&nbsp;
				[{$class.object_count}]
				&nbsp;&nbsp;
				<a href={concat( 'class/edit/', $class.id, '/(language)/', $class.top_priority_language_locale )|ezurl} title="{'Edit the <%class_name> class.'|i18n( 'design/admin/class/classlist',, hash( '%class_name', $class.name ) )|wash}"><img class="button" src={'edit.gif'|ezimage} width="16" height="16" alt="edit" /></a>
			</strong>
		{else}
			<strong>Choose Class :</strong>
		{/if}
		&nbsp;&nbsp;
		<select id="classes_list" onchange="easypublishClassSwitchClass(event);">
			<option value="">------Switch Class------</option>
			{def $classes=array()}
			{foreach $groups as $group}
				<optgroup label="{$group.name|wash}">
				{set $classes=fetch('class','list_by_groups',hash('group_filter',array($group.id)))}
				{foreach $classes as $cs}
					<option value="{$cs.identifier}" {if and(is_set($class),eq($class.identifier,$cs.identifier))}selected="selected"{/if}>{$cs.name}</option>
				{/foreach}
				</optgroup>
			{/foreach}
			{undef}
		</select>
	</div>
</div>
</div>
</div>
</div>
</div>
</div>

{*Table content*}
<div class="box-ml">
<div class="box-mr">
<div class="box-content">
<table class="list" cellspacing="1">
	<tr>
		<th>Name</th>
		<th>Identifier</th>
		<th>Object name pattern</th>
		<th>Url alias</th>
		<th>Container</th>
		<th>Group</th>
		<th>Sort of Children</th>
		<th>Availablity</th>
	</tr>
	<tr class="bglight">
		{if is_set($class) }
			<td><input type="text" name="class_name" value="{$class.name}" /></td>
			<td>
				<input type="text" name="class_identifier" value="{$class.identifier}" />
				<input type="hidden" name="class_original_identifier" value="{$class.identifier}" />
			</td>
			<td><input name="class_object_name_pattern" type="text" value="{$class.contentobject_name}" /></td>
			<td><input name="class_url_alias_name" size="5" type="text" value="{$class.url_alias_name}" /></td>
			<td><input name="class_is_container" type="checkbox" {if $class.is_container}checked="checked"{/if} /></td>
			<td><select name="class_group" class="short_param">
				{foreach $groups as $group}
					{if eq($group.id, $class.ingroup_id_list.0) }
						<option value="{$group.id}" selected="selected" >{$group.name}</option>
					{else}
						<option value="{$group.id}">{$group.name}</option>
					{/if}
				{/foreach}
				</select>
			</td>
			<td>
				{def $sort_fields=fetch( content, available_sort_fields )}
			    <select name="class_default_sorting_field" class="short_param">
			    {foreach $sort_fields as $sf_key => $sf_item}
			        <option value="{$sf_key}" {if eq( $sf_key, $class.sort_field )}selected="selected"{/if}>{$sf_item}</option>
			    {/foreach}
			    </select>
			    <select name="class_default_sorting_order" class="short_param">
			        <option value="0"{if eq($class.sort_order, 0)} selected="selected"{/if}>{'Descending'|i18n( 'design/admin/class/edit' )}</option>
			        <option value="1"{if eq($class.sort_order, 1)} selected="selected"{/if}>{'Ascending'|i18n( 'design/admin/class/edit' )}</option>
			    </select>
			    {undef}
			</td>
			<td>
				<input name="class_always_available" type="checkbox" {if and($class.always_available)}checked="checked"{/if} />
				
				<input type="hidden" name="class_version" value="{$class.version}" />
				<input type="hidden" name="class_id" value="{$class.id}" />
			</td>
		{else}
			<td><input type="text" name="class_name" /></td>
			<td>
				<input type="text" name="class_identifier" />
				<input type="hidden" name="class_original_identifier" value="" />
			</td>
			<td><input name="class_object_name_pattern" type="text" /></td>
			<td><input name="class_url_alias_name" size="5" type="text" /></td>
			<td><input name="class_is_container" type="checkbox" /></td>
			<td><select name="class_group" class="short_param">
				{foreach $groups as $group}
					<option value="{$group.id}">{$group.name}</option>
				{/foreach}
				</select>
			</td>
			<td>
				{def $sort_fields=fetch( content, available_sort_fields )}
			    <select name="class_default_sorting_field" class="short_param">
			    {foreach $sort_fields as $sf_key => $sf_item}
			        <option value="{$sf_key}" {if eq( $sf_key, 1 )}selected="selected"{/if}>{$sf_item}</option>
			    {/foreach}
			    </select>
			    <select name="class_default_sorting_order" class="short_param">
			        <option value="0">{'Descending'|i18n( 'design/admin/class/edit' )}</option>
			        <option value="1" selected="selected">{'Ascending'|i18n( 'design/admin/class/edit' )}</option>
			    </select>
			    {undef}
			</td>
			<td>
				<input name="class_always_available" type="checkbox" />
				<input type="hidden" name="class_version" value="0" />
				<input type="hidden" name="class_id" value="0" />
			</td>
		{/if}
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
	<th>Default</th>
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
			<select name="attribute_datatype[{$i}]" id="attribute_datatype[{$i}]" class="short_type">
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
		<td><input type="text" name="attribute_defaultvalue[{$i}]" size="5" /></td>
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
</div>
</div>

</div>

</form>