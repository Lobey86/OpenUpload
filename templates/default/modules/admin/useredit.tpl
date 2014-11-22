{include file="default/modules/admin/adminmenu.tpl"}
<form action="{$script}" method="post">
<input type="hidden" name="action" value="{$action}">
<input type="hidden" name="step" value="{$step}">
<input type="hidden" name="login" value="{$edituser.login}">
<table border="0">
<tr><td>{tr}Login name{/tr}:</td><td>{$edituser.login}</td></tr>
<tr><td>{tr}Password{/tr}:</td><td><input type="password" name="edituserpassword"></td></tr>
<tr><td>{tr}Retype Password{/tr}:</td><td><input type="password" name="edituserrepassword"></td></tr>
<tr><td>{tr}Full Name{/tr}:</td><td><input type="text" name="editusername" value="{$edituser.name}"></td></tr>
<tr><td>{tr}e-mail{/tr}:</td><td><input type="text" name="edituseremail" value="{$edituser.email}"></td></tr>
<tr><td>{tr}Group{/tr}:</td><td><select name="editusergroup">
   {foreach from=$groups item=g}
     <option value="{$g.name}" {if $g.name==$edituser.group_name} selected{/if}>{$g.description}</option>
  {/foreach}
  </select></td></tr>
<tr><td>{tr}Preferred language{/tr}:</td><td> <select name="edituserlang">
{foreach from=$langs item=l}<option value="{$l.id}" {if $edituser.lang==$l.id}selected{/if}>{$l.name}</option>{/foreach}
</select></td></tr>
<tr><td>{tr}Active{/tr}:</td><td><input type="checkbox" name="edituseractive" value="1" {if $edituser.active==1}checked{/if}></td></tr>
<tr><td colspan="2" align=right><input type="submit" class="submit" value="{tr}Confirm{/tr}"></td></tr>
</table>
</form>