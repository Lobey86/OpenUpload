<form action="{$script}" method="POST">
<input type="hidden" name="action" value="{$action}">
<input type="hidden" name="step" value="{$step}">
<table border="0" width="300">
<tr><td>{tr}Login name{/tr}:</td><td>{$puser.login}</td></tr>
<tr><td>{tr}Full Name{/tr}:</td><td><input type="text" name="username" value="{$puser.name}"></td></tr>
<tr><td>{tr}e-mail{/tr}:</td><td><input type="text" name="useremail" value="{$puser.email}"></td></tr>
<tr><td>{tr}Preferred language{/tr}:</td><td> <select name="userlang">
{foreach from=$langs item=l}<option value="{$l.id}" {if $user.lang==$l.id}selected{/if}>{$l.name}</option>{/foreach}
</select></td></tr>
<tr><td colspan="2"><hr></td></tr>
<tr><td>{tr}Old password{/tr}:</td><td><input type="password" name="oldpassword" value=""></td></tr>
<tr><td>{tr}New password{/tr}:</td><td><input type="password" name="newpassword" value=""></td></tr>
<tr><td>{tr}Retype password{/tr}:</td><td><input type="password" name="confirmpassword" value=""></td></tr>
<tr><td><a href="{$script}?action={$action}&step=1">&lt;&lt; {tr}Cancel{/tr}</td>
    <td align="right"><input type="submit" class="submit" value="{tr}Confirm{/tr}"></td></tr>
</table>
