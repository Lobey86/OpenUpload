{include file="default/modules/admin/adminmenu.tpl"}
<script>
msg1 = '{tr}Are you sure you want to delete the selected users?{/tr}';
msg2 = '{tr}Are you sure you want to delete the selected user?{/tr}';
{include file="default/modules/admin/deletescript.tpl"}
</script>
<div id="toolbar">
<a href="{$script}?action={$action}&step=2&id={$u.id}"><img src="{tpl file=/img/admin/tadd_user.png}"></a>
<a href="{$script}?action={$action}&step={$step}" onclick="return multidelete();"><img src="{tpl file=/img/admin/tdelete_user.png}"></a>
</div>
<br />
<form name="deleteform" id="deleteform" action="{$script}" method="POST">
<input type="hidden" name="action" value="{$action}">
<input type="hidden" name="step" value="6">
<table border="0" id="dbtable">
<tr>
  <th width="10">S</th>
  <th width="100">{tr}Login{/tr}</th>
  <th width="200">{tr}Name{/tr}</th>
  <th width="100">{tr}Group{/tr}</th>
  <th width="200">{tr}E-mail{/tr}</th>
  <th width="20">{tr}Active{/tr}</th>
  <th width="100">{tr}Actions{/tr}</th>
</tr>
{foreach from=$users item=u}
{cycle values="row1,row2" advance=true assign=rid}
<tr>
  <td id="{$rid}"><input type="checkbox" name="user_{$u.id}" value="{$u.login}"></td>
  <td id="{$rid}"><a href="{$script}?action={$action}&step=3&id={$u.login}">{$u.login}</a></td>
  <td id="{$rid}">{$u.name}</td>
  <td id="{$rid}">{$u.group_name}</td>
  <td id="{$rid}">{$u.email}</td>
  <td id="{$rid}"><a href="{$script}?action={$action}&step=5&id={$u.login}&active={$u.active}">
                   <img src="{tpl file='/img/admin/active%s.png'|sprintf:$u.active}">
                </a>
   </td>
  <td id="{$rid}">
    <a href="{$script}?action={$action}&step=3&id={$u.login}"><img src="{tpl file=/img/admin/edit_user.png}"></a>
   &nbsp; 
   <a href="{$script}?action={$action}&step=4&id={$u.login}" onclick="return rowdelete();"><img src="{tpl file=/img/admin/delete_user.png}"></a></td>
</tr>
{/foreach}
</table>
</form>