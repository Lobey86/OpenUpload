{include file="default/modules/admin/adminmenu.tpl"}
{include file="default/modules/admin/filesmenu.tpl"}

<h2>{tr}Deletion Result{/tr}</h2>
<br />
<div id="message">
{if count($files)>0}
{if isset($deleted)}
<h3>{tr}The following files have been deleted.{/tr}</h3>
{foreach from=$files item=f}
<div id="message">{$f.id}</div>
{/foreach}
{else}
<h3>{tr}The following files will be deleted, proceed?{/tr}</h3>
<form action="{$script}" method="POST">
<input type="hidden" name="action" value="{$action}">
<input type="hidden" name="step" value="{$step}">
<input type="submit" name="delete" class="submit" value="{tr}Yes, delete all{/tr}">
</form>
{foreach from=$files item=f}
<div id="message">{$f.id}</div>
{/foreach}

{/if}
{else}
{tr}No files matched the criteria{/tr}
{/if}
</div>
<br />
<a href="{$script}?action={$action}">&lt;&lt; {tr}Back to Maintenance{/tr}</a>