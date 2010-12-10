<div class="block">
    <label>Select ini file to view:&nbsp;
    <select name="bysoftdeveloperSelectedINIFile" id="bysoftdeveloperSelectedINIFile">
        {section var=Files loop=$ini_files}
			<option value="{$Files.item}">{$Files.item}</option>
        {/section}
    </select></label>
    <label>Select siteaccess:&nbsp;
    <select name="bysoftdeveloperCurrentSiteAccess" id="bysoftdeveloperCurrentSiteAccess">
        {section name=SiteAccess loop=$siteaccess_list}
			<option value="{$:item}">{$:item}</option>
        {/section}
    </select></label>
    <input type="button" value="Select" onclick="javascript:bysoftdeveloperChangeIniFile();return false;">
</div>