{include file="default/modules/admin/adminmenu.tpl"}
<script>
msg1 = '{tr}Are you sure you want to delete the selected groups?{/tr}';
msg2 = '{tr}Are you sure you want to delete the selected group?{/tr}';
{include file="default/modules/admin/deletescript.tpl"}
</script>
<div id="toolbar">
<a href="{$script}?action={$action}&step=2&id={$u.id}"><img src="{tpl file=/img/admin/tadd_group.png}"></a>
<a href="{$script}?action={$action}&step={$step}" onclick="return multidelete();"><img src="{tpl file=/img/admin/tdelete_group.png}"></a>
</div>
<br />
<form name="deleteform" id="deleteform" action="{$script}" method="POST">
<input type="hidden" name="action" value="{$action}">
<input type="hidden" name="step" value="5">
<table border="0" id="dbtable">
<tr>
  <th width="10">S</th>
  <th width="100">{tr}Group Name{/tr}</th>
  <th width="200">{tr}Description{/tr}</th>
  <th width="100">{tr}Actions{/tr}</th>
</tr>
{foreach from=$groups item=g}
{cycle values="row1,row2" advance=true assign=rid}
<tr>
  <td id="{$rid}"><input type="checkbox" name="group_{$g.name}" value="1"></td>
  <td id="{$rid}" style="text-align:left"><a href="{$script}?action=admingroups&step=3&id={$g.name}">{$g.name}</a></td>
  <td id="{$rid}">{$g.description}</td>
  <td id="{$rid}">
    <a href="{$script}?action={$action}&step=3&id={$g.name}"><img src="{tpl file=/img/admin/edit_group.png}"></a>
   &nbsp; 
   <a href="{$script}?action={$action}&step=4&id={$g.name}" onclick="return rowdelete();"><img src="{tpl file=/img/admin/delete_group.png}"></a></td>
</tr>
{/foreach}
</table>
</form>
