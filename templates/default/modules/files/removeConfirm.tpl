{tr}You requested to remove the following file{/tr}:<br />
<form method="POST" action={$script}>
<input type="hidden" name="action" value="{$action}">
<input type="hidden" name="step" value="{$nextstep}">
<table border="0">
<tr><td>{tr}File description{/tr}:</td><td><b>{$finfo.description}</b></td></tr>
{foreach from=$files item=f}
<tr><td>{tr}File name{/tr}:</td><td><b>{$f.name}</b></td></tr>
<tr><td>{tr}File size{/tr}:</td><td>{$f.size|fsize_format}</td></tr>
{/foreach}
<tr><td>{tr}Uploaded on{/tr}:</td><td><b>{$finfo.upload_date}</b></td></tr>
{$plugins}
<tr><td colspan="2" align="right"><input class="submit" type="submit" name="confirmremove" value="{tr}Confirm removal{/tr}">
</table>
</form>