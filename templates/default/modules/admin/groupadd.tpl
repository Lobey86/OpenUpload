{include file="default/modules/admin/adminmenu.tpl"}

<form action="{$script}" method="post">
<input type="hidden" name="action" value="{$action}">
<input type="hidden" name="step" value="{$step}">
<table border="0">
<tr><td>{tr}Name{/tr}:</td><td><input type="text" name="addgroupname" value="{$group.name}"></td></tr>
<tr><td>{tr}Description{/tr}:</td><td><input type="text" name="addgroupdescription" value="{$group.description}"></td></tr>
<tr><td colspan="2" align=right><input type="submit" class="submit" value="{tr}Add{/tr}"></td></tr>
</table>
</form>