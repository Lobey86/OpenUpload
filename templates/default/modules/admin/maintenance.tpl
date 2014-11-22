{include file="default/modules/admin/adminmenu.tpl"}
{include file="default/modules/admin/filesmenu.tpl"}

<fieldset style="width: 100%">
<legend>{tr}Maintenence{/tr}</legend>
<p align="left">
{tr}This options let you delete files based on some options.{/tr}<br />
{tr}Please select one or more criteria for file deletion{/tr}<br />
<form action="{$script}" method="POST">
<input type="hidden" name="action" value="{$action}">
<input type="hidden" name="step" value="2">
<table border="0">
<tr><td><input type="checkbox" name="c_older" value="1" {if $criteria.c_older==1}checked{/if}> {tr}Delete files older than{/tr}</td><td><input size="4" type="text" name="older" value="{$criteria.older}"> {tr}days{/tr}</td></tr>
<tr><td><input type="checkbox" name="c_login" value="1" {if $criteria.c_login==1}checked{/if}> {tr}Which user name is{/tr}:</td><td>
{if isset($users)}
<select name="login">
{foreach from=$users item=u}
<option value="{$u.login}"{if $criteria.login==$u.login}selected{/if}>{$u.login}</option>
{/foreach}
</select>
{else}
<input size="20" type="text" name="login" value="{$criteria.login}">
{/if}</td></tr>
<tr><td><input type="checkbox" name="c_date" value="1" {if $criteria.c_date==1}checked{/if}> {tr}Which upload day is{/tr}:</td><td><input size="14" type="text" name="date" value="{$criteria.date}"> (yyyy-mm-dd)</td></tr>
<tr><td><input type="checkbox" name="c_size" value="1" {if $criteria.c_size==1}checked{/if}> {tr}Which size is bigger than{/tr}:</td><td><input size="10" type="text" name="size" value="{$criteria.size}"> MB</td></tr>
<tr><td colspan="2">&nbsp;</td>
<tr><td colspan="2" align="left"><input type="submit" class="submit" name="run" value="{tr}Proceed{/tr}"></td>
</table>
</form>
</fieldset>
{if $expireplugin=='yes'}
<br /><hr><br />
<fieldset style="text-align: left; width: 100%;">
<legend>{tr}Expiration plugin{/tr}</legend>
<p align="left">
{tr}To delete files marked as expired by the expire plugin press the "Delete expired" button.{/tr}
<form action="{$script}" method="POST">
<input type="hidden" name="action" value="{$action}">
<input type="hidden" name="step" value="2">
<input type="submit" name="expire" class="submit" value="{tr}Delete expired{/tr}">
</form>
</p>
</fieldset>
{/if}
