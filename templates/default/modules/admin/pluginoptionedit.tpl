{include file="default/modules/admin/adminmenu.tpl"}

<form action="{$script}" method="post">
<input type="hidden" name="action" value="{$action}">
<input type="hidden" name="step" value="{$step}">
<input type="hidden" name="id" value="{$pluginname}">
<input type="hidden" name="gid" value="{$gid}">
<table border="0">
<tr><td>{tr}Group{/tr}:</td><td>{$gid}</td></tr>
{foreach from=$options item=o}
<tr><td valign="top">{$o.description}:</td><td>{if $o.type=='list'}
<textarea cols="40" rows="6" name="{$o.name}">{$plugin_options[$o.name].value}</textarea>
{elseif $o.type=='text'}
<input type="text" name="{$o.name}" value="{$plugin_options[$o.name].value}"></td></tr>
{/if}
{/foreach}
<tr><td colspan="2" align=right><input type="submit" class="submit" value="{tr}Confirm{/tr}"></td></tr>
</table>
</form>