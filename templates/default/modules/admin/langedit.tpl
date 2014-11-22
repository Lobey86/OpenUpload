{include file="default/modules/admin/adminmenu.tpl"}

<form action="{$script}" method="post">
<input type="hidden" name="action" value="{$action}">
<input type="hidden" name="step" value="{$step}">
<input type="hidden" name="id" value="{$lang.id}">
<table border="0">
<tr><td>{tr}ID{/tr}:</td><td>{$lang.id}</td></tr>
<tr><td>{tr}Name{/tr}:</td><td><input type="text" name="editlangname" value="{$lang.name}"></td></tr>
<tr><td>{tr}Locale{/tr}:</td><td><input type="text" name="editlanglocale" value="{$lang.locale}"></td></tr>
<tr><td>{tr}Browser recon{/tr}:</td><td><input type="text" name="editlangbrowser" value="{$lang.browser}"></td></tr>
<tr><td>{tr}Charset{/tr}:</td><td><input type="text" name="editlangcharset" value="{$lang.charset}"></td></tr>
<tr><td>{tr}Active{/tr}:</td><td><input type="checkbox" name="editlangactive" value="1" {if $lang.active==1}checked{/if}></td></tr>
<tr><td colspan="2" align=right><input type="submit" class="submit" value="{tr}Confirm{/tr}"></td></tr>
</table>
</form>