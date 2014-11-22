{include file="default/modules/admin/adminmenu.tpl"}

<form action="{$script}" method="post">
<input type="hidden" name="action" value="{$action}">
<input type="hidden" name="step" value="{$step}">
<table border="0">
<tr><td>{tr}Login name{/tr}:</td><td><input type="text" name="adduserlogin" value="{$adduser.login}"></td></tr>
<tr><td>{tr}Password{/tr}:</td><td><input type="password" name="adduserpassword"></td></tr>
<tr><td>{tr}Retype Password{/tr}:</td><td><input type="password" name="adduserrepassword"></td></tr>
<tr><td>{tr}Full Name{/tr}:</td><td><input type="text" name="addusername" value="{$adduser.name}"></td></tr>
<tr><td>{tr}e-mail{/tr}:</td><td><input type="text" name="adduseremail" value="{$adduser.email}"></td></tr>
<tr><td>{tr}Group{/tr}:</td><td><select name="addusergroup">
   {foreach from=$groups item=g}
     <option value="{$g.name}" {if $g.name==$adduser.group_name} selected{/if}>{$g.description}</option>
  {/foreach}
  </select></td></tr>
<tr><td>{tr}Preferred language{/tr}:</td><td> <select name="adduserlang">
{foreach from=$langs item=l}<option value="{$l.id}" {if $adduser.lang==$l.id}selected{/if}>{$l.name}</option>{/foreach}
</select></td></tr>
<tr><td>{tr}Active{/tr}:</td><td><input type="checkbox" name="adduseractive" value="1" {if $adduser.active==1}checked{/if}></td></tr>
<tr><td colspan="2" align=right><input type="submit" class="submit" value="{tr}Add{/tr}"></td></tr>
</table>
</form>