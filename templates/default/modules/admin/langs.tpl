{include file="default/modules/admin/adminmenu.tpl"}
<script>
msg1 = '{tr}Are you sure you want to delete the selected languages?{/tr}';
msg2 = '{tr}Are you sure you want to delete the selected language?{/tr}';
{include file="default/modules/admin/deletescript.tpl"}
</script>
<div id="toolbar">
<a href="{$script}?action={$action}&step=2&id={$u.id}"><img src="{tpl file=/img/admin/tadd.png}"></a>
<a href="{$script}?action={$action}&step={$step}" onclick="return multidelete();"><img src="{tpl file=/img/admin/tdelete.png}"></a>
</div>
<br />
<form name="deleteform" id="deleteform" action="{$script}" method="POST">
<input type="hidden" name="action" value="{$action}">
<input type="hidden" name="step" value="6">
<table border="0" id="dbtable">
<tr>
  <th width="10">S</th>
  <th width="100">{tr}ID{/tr}</th>
  <th width="200">{tr}Name{/tr}</th>
  <th width="100">{tr}Locale{/tr}</th>
  <th width="20">{tr}Active{/tr}</th>
  <th width="100">{tr}Actions{/tr}</th>
</tr>
{foreach from=$langlist item=l}
{cycle values="row1,row2" advance=true assign=rid}
<tr>
  <td id="{$rid}"><input type="checkbox" name="lang_{$l.id}" value="1"></td>
  <td id="{$rid}"><a href="{$script}?action={$action}&step=3&id={$l.id}">{$l.id}</a></td>
  <td id="{$rid}">{$l.name}</td>
  <td id="{$rid}">{$l.locale}</td>
  <td id="{$rid}"><a href="{$script}?action={$action}&step=5&id={$l.id}&active={$l.active}">
                   <img src="{tpl file='/img/admin/active%s.png'|sprintf:$l.active}">
                </a>
   </td>
  <td id="{$rid}">
    <a href="{$script}?action={$action}&step=3&id={$l.id}"><img src="{tpl file=/img/admin/edit_lang.png}"></a>
   &nbsp; 
   <a href="{$script}?action={$action}&step=4&id={$l.id}" onclick="return rowdelete();"><img src="{tpl file=/img/admin/delete.png}"></a></td>
</tr>
{/foreach}
</table>
</form>