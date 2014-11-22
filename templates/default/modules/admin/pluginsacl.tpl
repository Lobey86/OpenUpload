{include file="default/modules/admin/adminmenu.tpl"}
<script>
msg1 = '{tr}Are you sure you want to delete the selected plugins?{/tr}';
msg2 = '{tr}Are you sure you want to delete the selected plugin?{/tr}';
{include file="default/modules/admin/deletescript.tpl"}
</script>
<div id="toolbar">
<a href="{$script}?action={$action}&step=2&id={$u.id}"><img src="{tpl file=/img/admin/plugins.png}"></a>
<a href="{$script}?action={$action}&step={$step}" onclick="return multidelete();"><img src="{tpl file=/img/admin/tdelete.png}"></a>
</div>
<br />
<form name="deleteform" id="deleteform" action="{$script}" method="POST">
<input type="hidden" name="action" value="{$action}">
<input type="hidden" name="step" value="5">
<table border="0" id="dbtable">
<tr>
  <th width="10">{tr}S{/tr}</th>
  <th width="30">{tr}ID{/tr}</th>
  <th width="100">{tr}Plugin{/tr}</th>
  <th width="100">{tr}Group{/tr}</th>
  <th width="100">{tr}Access{/tr}</th>
  <th width="100">{tr}Actions{/tr}</th>
</tr>
{foreach from=$plugins_acl item=p}
{cycle values="row1,row2" advance=true assign=rid}
<tr>
  <td id="{$rid}"><input type="checkbox" name="p_{$p.id}" value="1"></td>
  <td id="{$rid}" style="text-align:left"><a href="{$script}?action={$action}&step=3&id={$p.id}">{$p.id}</a></td>
  <td id="{$rid}">{$p.plugin}</td>
  <td id="{$rid}">{$p.group_name}</td>
  <td id="{$rid}">{$p.access}</td>
  <td id="{$rid}">
    <a href="{$script}?action={$action}&step=3&id={$p.id}"><img src="{tpl file=/img/admin/edit_plugin.png}"></a>
   &nbsp; 
   <a href="{$script}?action={$action}&step=4&id={$p.id}" onclick="return rowdelete();"><img src="{tpl file=/img/admin/delete.png}"></a></td>
</tr>
{/foreach}
</table>