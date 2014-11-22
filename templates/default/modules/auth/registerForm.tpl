<form method="POST" action="{$script}">
<input type="hidden" name="action" value="{$action}">
<input type="hidden" name="step" value="{$nextstep}">
<table border="0">
<tr><td>{tr}Login name{/tr}:</td><td><input type="text" name="registerlogin" value="{$register.login}"></td></tr>
<tr><td>{tr}Password{/tr}:</td><td><input type="password" name="registerpassword"></td></tr>
<tr><td>{tr}Retype Password{/tr}:</td><td><input type="password" name="registerrepassword"></td></tr>
<tr><td>{tr}Full Name{/tr}:</td><td><input type="text" name="registername" value="{$register.name}"></td></tr>
<tr><td>{tr}e-mail{/tr}:</td><td><input type="text" name="registeremail" value="{$register.email}"></td></tr>
<tr><td>{tr}Preferred language{/tr}:</td><td> <select name="registerlang">
{foreach from=$langs item=l}<option value="{$l.id}" {if $user.lang==$l.id}selected{/if}>{$l.name}</option>{/foreach}
</select></td></tr>
{$plugins}
<tr><td colspan="2" align="right"><input type="submit" class="submit" value="Register"></td></tr>
</table>
</form>