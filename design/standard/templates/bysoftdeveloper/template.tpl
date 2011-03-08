{literal}
<html>
<head>
<title>Operators & Functions</title>
<style type="text/css">
#wrap {
	margin:0 auto;
	width: 99%
}
tr {
	font-size: 1.1em;
	text-align: left;
}
td{
	border-color: #EAEAEA;
	border-bottom:1px solid #EAEAEA;
	border-right:1px solid #EAEAEA;
	padding: 0.25em 0.2em;
	text-align: left;
	vertical-align:top;
	white-space:nowrap
}
td a{
	color: #000000;
	text-decoration: none;
}
th{
	text-align: left;
	font-size: 1.2em;
	border-right: 1px solid #EAEAEA;
	border-bottom:1px solid #EAEAEA;
}
table{
	border-left:1px solid #EAEAEA;
	border-top:1px solid #EAEAEA;
	width: 100%;
}
strong {
	font-size: 1.2em;
}
table.operator strong, table.operator th{
	color: #F15E22;
}
table.function strong, table.function th{
	color: #000099;
}
hr {
	color: #007700;
}
table.line td{
	text-align: right;
}

table.line td.category{
	width: 50%;
	text-align: left;
}
#window{
	background-color:white;
	border:2px solid green;
	position:absolute;
	height: 200px;
	width: 400px;
	z-index:996;
	top: 40%;
	left: 40%;
}
#name{
	background-color:green;
	color:white;
	cursor:pointer;
}
textarea{
	width: 100%;
	border: 0;
	border-bottom: 1px solid #EAEAEA;
}
</style>
<script type="text/javascript">

function showInfo(name, path, evt){
	var w = document.getElementById('window');
	
	document.getElementById('name').innerHTML = name;
	document.getElementById('path').value = path;
	w.style.display = 'block';
	
	document.getElementById('path').select();
	showTips(w, evt);
}

function showTips(obj, evt)
{

    var fixed = 14;
    var xPos = parseInt(evt.clientX);
    var yPos = parseInt(evt.clientY);
    
    var scrollTop;
    var scrollLeft;
    var clientWidth;
    var clientHeight;
    var scrollWidth;
    var scrollHeight;

    if (document.documentElement) {
        scrollTop = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop;
        scrollLeft = document.documentElement.scrollLeft ? document.documentElement.scrollLeft : document.body.scrollLeft;
        clientWidth = document.documentElement.clientWidth ? document.documentElement.clientWidth : document.body.clientWidth;
        clientHeight = document.documentElement.clientHeight ? document.documentElement.clientHeight : document.body.clientHeight;
        scrollWidth = document.documentElement.scrollWidth ? document.documentElement.scrollWidth : document.body.scrollWidth;
        scrollHeight = document.documentElement.scrollHeight ? document.documentElement.scrollHeight : document.body.scrollHeight;
    } else if (document.body) {
        scrollTop = document.body.scrollTop;
        scrollLeft = document.body.scrollLeft;
        clientWidth = document.body.clientWidth;
        clientHeight = document.body.clientHeight;
        scrollWidth = document.body.scrollWidth;
        scrollHeight = document.body.scrollHeight;
    }
    scrollTop = parseInt(scrollTop);
    scrollLeft = parseInt(scrollLeft);
    clientWidth = parseInt(clientWidth);
    clientHeight = parseInt(clientHeight);
    scrollWidth = parseInt(scrollWidth);
    scrollHeight = parseInt(scrollHeight);
    
    objHeight = obj.offsetHeight;
    objWidth = obj.offsetWidth;
    
    if (xPos + objWidth > scrollWidth) {
        obj.style.left = xPos - objWidth - fixed +  scrollLeft + 'px';
    } else if (xPos + objWidth > clientWidth) {
        obj.style.left = xPos - objWidth - fixed + scrollLeft + 'px';
    } else {
        obj.style.left = xPos + fixed + scrollLeft + 'px';
    }
    if (yPos + objHeight > scrollHeight) {
        obj.style.top =  yPos - objHeight - fixed + scrollTop + 'px';
    } else if (yPos + objHeight > clientHeight){
        obj.style.top = yPos - objHeight - fixed + scrollTop + 'px';
    } else {
        obj.style.top = yPos + fixed + scrollTop + 'px';
    }
    
}


function closeWindow(){
	document.getElementById('window').style.display = 'none';
}
</script>
</head>
<body>
{/literal}

<div id="window" style="display:none">
	<div id="name" onclick="javascript:closeWindow();">name</div>
	<div><textarea id="path"  type="text" onclick="javascript:this.select();" value="" /></textarea></div>
</div>

<div id="wrap">

{def $length=10 $offset=0 $only_a_line=false()}
{foreach $template_operators as $category => $operators}
	{set $only_a_line=false()}
	{switch match=$category}
		{case match='Arrays'}{set $length=12}{/case}
		{case match='Data and information extraction'}{set $only_a_line=true()}{/case}
		{case match='Formatting and internationalization'}{set $only_a_line=true()}{/case}
		{case match='Miscellaneous'}{set $length=9}{/case}
		{case match='Strings'}{set $length=12}{/case}
		{case match='Logical operations'}{set $length=16 $only_a_line=true() }{/case}
		{case match='Variable and type handling'}{set $length=16}{/case}
		{case match='Mathematics'}{set $length=20 $only_a_line=true()}{/case}
		{case match='Images'}{set $only_a_line=true()}{/case}
		{case match='URLs'}{set $only_a_line=true()}{/case}
		{case match='XExtension Operator'}{set $length=7}{/case}
	{/switch}
	{if $only_a_line}
		<table class="operator line">
			<tr><td class="category"><strong>{$category}</strong></td>
			{foreach $operators as $info}
				<td><a onclick="showInfo('{$info.name}', '{$info.path}', event);" title="{$info.script}">{$info.name}</a></td>
			{/foreach}
			</tr>
		</table>
	{else}
		<table class="operator">
			<thead><th colspan="{$length}">{$category}</th></thead>
			{foreach $operators|array_intel($length,0) as $groups}
				<tr>
					{foreach $groups as $info}
						<td><a onclick="showInfo('{$info.name}', '{$info.path}', event);" title="{$info.script}">{$info.name}</a></td>
					{/foreach}
				</tr>
			{/foreach}
		</table>
	{/if}
{/foreach}
<hr />
{set $length=10}
{foreach $template_functions as $category => $functions}	
	{set $only_a_line=false()}
	{switch match=$category}
		{case match='Miscellaneous'}{set $length=12 $only_a_line=true() }{/case}
		{case match='Variables'}{set $length=12 $only_a_line=true()}{/case}
		{case match='Miscellaneous'}{set $length=16}{/case}
		{case match='Debugging'}{set $length=12 $only_a_line=true()}{/case}
		{case match='Visualization'}{set $length=6}{/case}
		{case match='XExtension Functions'}{set $length=7 $only_a_line=true()}{/case}
	{/switch}
	{if $only_a_line}
		<table class="function line">
			<tr><td class="category"><strong>{$category}</strong></td>
			{foreach $functions as $info}
				<td><a onclick="showInfo('{$info.name}', '{$info.path}', event);" title="{$info.script}">{$info.name}</a></td>
			{/foreach}
			</tr>
		</table>
	{else}
		<table class="function">
			<thead><th colspan="{$length}">{$category}</th></thead>
			{foreach $functions|array_intel($length,0) as $groups}
				<tr>
					{foreach $groups as $info}
						<td><a onclick="showInfo('{$info.name}', '{$info.path}', event);" title="{$info.script}">{$info.name}</a></td>
					{/foreach}
				</tr>
			{/foreach}
		</table>
	{/if}
{/foreach}

<hr />
<table><tr><td><strong>Function Attributes</strong></td>
	{foreach $template_funcattrs as $attr => $info}
		<td><a onclick="showInfo('{$info.name}', '{$info.path}', event);" title="{$info.script}">{$attr}</a></td>
	{/foreach}
</tr></table>

</div>
</body>
</html>
