{def $account = null $groups = array() $roles = array()}
{def $td_style = 'style="font-weight:bold;"'}
<table class="list">
	<tr>
		<th>Object ID</th>
		<th>name</th>
		<th>login</th>
		<th>email</th>
		<th>Locations</th>
		<th>Groups</th>
		<th>Roles</th>
		<th>Switch</th>
	</tr>
	{foreach $userObjectList as $userobject}
	{set $groups = array()}
	{set $roles = array()}
	<tr class="bglight" {if $userobject.contentobject_id|eq($current_user.contentobject_id)}style="border: 2px dashed orange;"{/if}>
		<td>{$userobject.contentobject_id}</td>
		<td>{$userobject.contentobject.name}</td>
		<td>{$userobject.login}</td>
		<td>{$userobject.email}</td>
		<td>
			{foreach $userobject.contentobject.assigned_nodes as $node}
			{set $groups = $groups|append($node.parent)}
			<a href="{$node.url_alias|ezurl(no)}" target="_blank">{$node.url}</a>
				{delimiter} <br/> {/delimiter}
			{/foreach}
		</td>
		<td>
			{set $groups = $userobject|getusergroups()}
			{foreach  $groups as $group}
			<a href="{$group.main_node.url_alias|ezurl(no)}" target="_blank">{$group.main_node.url}</a>
				{delimiter} <br/> {/delimiter}
			{/foreach}
		</td>
		<td>
			{set $roles = $userobject.roles}
			{foreach  $roles as $role}
			<a href="{concat('role/view/', $role.id)|ezurl(no)}" target="_blank">{$role.name}</a>
				{delimiter} <br/> {/delimiter}
			{/foreach}
		</td>
		<td>
			{if $userobject.contentobject_id|eq($current_user.contentobject_id)}
			 Logined
			{else}
			<form name="bysoftdeveloper-user-switchform" action="{concat('bysoftdeveloper/user'|ezurl(no))}">
				<input type="hidden" value="{$userobject.contentobject_id}" name="uid"/>
				<input type="submit" value="Login this" >
			</form>
			{/if}
		</td>
	</tr>
	{/foreach}
</table>