<div class="block">
	{def $classes=fetch('class','list')}
	<label>Select class:&nbsp;
	<select name="bysoftdeveloperSelectedClass" id="bysoftdeveloperSelectedClass" onchange="javascript:bysoftdeveloperChangeClass();">
		<option value="">--Please choose class--</option>
		{def $classes=array()}
		{foreach $groups as $group}
			<optgroup label="{$group.name|wash}">
			{set $classes=fetch('class','list_by_groups',hash('group_filter',array($group.id)))}
			{foreach $classes as $cs}
				<option value="{$cs.identifier}">{$cs.name}</option>
			{/foreach}
			</optgroup>
		{/foreach}
	</select></label>
</div>