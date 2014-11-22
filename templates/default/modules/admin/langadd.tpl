{include file="default/modules/admin/adminmenu.tpl"}

<form action="{$script}" method="post">
<input type="hidden" name="action" value="{$action}">
<input type="hidden" name="step" value="{$step}">
<table border="0">
<tr><td>{tr}ID{/tr}:</td><td><input type="text" name="addlangid" value="{$lang.id}"></td></tr>
<tr><td>{tr}Name{/tr}:</td><td><input type="text" name="addlangname" value="{$lang.name}"></td></tr>
<tr><td>{tr}Locale{/tr}:</td><td><input type="text" name="addlanglocale" value="{$lang.locale}"></td></tr>
<tr><td>{tr}Browser recon{/tr}:</td><td><input type="text" name="addlangbrowser" value="{$lang.browser}"></td></tr>
<tr><td>{tr}Charset{/tr}:</td><td><input type="text" name="addlangcharset" value="{$lang.charset}"></td></tr>
<tr><td>{tr}Active{/tr}:</td><td><input type="checkbox" name="addlangactive" value="1" {if $lang.active==1}checked{/if}></td></tr>
<tr><td colspan="2" align=right><input type="submit" class="submit" value="{tr}Confirm{/tr}"></td></tr>
</table>
</form>