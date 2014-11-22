{include file="default/modules/admin/adminmenu.tpl"}

<form action="{$script}" method="post">
<input type="hidden" name="action" value="{$action}">
<input type="hidden" name="step" value="{$step}">
<table border="0">
<tr><td>{tr}Plugin{/tr}:</td><td>
<select name="addpluginplugin">
{foreach from=$pluginslist item=p}
<option value="{$p}" {if $p==$plugin.plugin}selected{/if}>{$p}</option>
{/foreach}
</select></td></tr>
<tr><td>{tr}Group{/tr}:</td><td>
<select name="addplugingroup">
{foreach from=$groups item=g}
<option value="{$g.name}" {if $g.name==$plugin.group_name}selected{/if}>{$g.name}</option>
{/foreach}
</select></td></tr>
<tr><td>{tr}Access{/tr}:</td><td>
<select name="addpluginaccess">
{foreach from=$access item=a key=k}
<option value="{$k}" {if $k==$plugin.access}selected{/if}>{$a}</option>
{/foreach}
</select></td></tr>
<tr><td colspan="2" align=right><input type="submit" class="submit" value="{tr}Add{/tr}"></td></tr>
</table>
</form>