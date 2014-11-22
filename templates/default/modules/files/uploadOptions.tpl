<div id="uploaddetail">
<form action="{$script}" method="POST">
<input type="hidden" name="action" value="{$action}">
<input type="hidden" name="step" value="{$nextstep}">
<table border="0">
{foreach from=$files item=f}
<tr><td>{tr}File name{/tr}:</td><td>{$f.name}</td></tr>
<tr><td>{tr}File size{/tr}:</td><td>{$f.size|fsize_format}</td></tr>
{/foreach}
<tr><td>{tr}Description{/tr}:</td><td><input id="description" type="text" size="30" name="description" value="{$finfo.description}"></td></tr>
{$plugins}
<tr><td colspan="2" align="right"><input class="submit" type="submit" value="{tr}Complete upload{/tr}"></td></tr>
</table>
</form>
</div>
{literal}
<script>
document.getElementById('description').focus();
</script>
{/literal}