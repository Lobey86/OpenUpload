{include file="default/modules/admin/adminmenu.tpl"}
<script>
msg1 = '{tr}Are you sure you want to delete the selected banned ips?{/tr}';
msg2 = '{tr}Are you sure you want to delete the selected banned ip?{/tr}';
{include file="default/modules/admin/deletescript.tpl"}
</script>
<div id="toolbar">
<a href="{$script}?action={$action}&step=2"><img src="{tpl file=/img/admin/tadd.png}"></a>
<a href="{$script}?action={$action}&step={$step}" onclick="return multidelete();"><img src="{tpl file=/img/admin/tdelete.png}"></a>
</div>
<br />
{if $pages>2}
<center>{section name=page loop=$pages start=1 max=20}
{if $pagen==$smarty.section.page.index}
<b style="font-size: 12pt">{$smarty.section.page.index}</b>
{else}
<a style="font-size: 12pt" href="{$script}?action={$action}&page={$smarty.section.page.index}">{$smarty.section.page.index}</a>
{/if}
&nbsp;&nbsp;
{/section} </center>
{/if}
<br />
<form name="deleteform" id="deleteform" action="{$script}" method="POST">
<input type="hidden" name="action" value="{$action}">
<input type="hidden" name="step" value="5">
<table border="0" id="dbtable">
<tr>
  <th width="10">{tr}S{/tr}</th>
  <th width="200">{tr}IP{/tr}</th>
  <th width="50">{tr}Access{/tr}</th>
  <th width="80">{tr}Priority{/tr}</th>
  <th width="100">{tr}Actions{/tr}</th>
</tr>
{foreach from=$banned item=b}
{cycle values="row1,row2" advance=true assign=rid}
<tr>
  <td id="{$rid}"><input type="checkbox" name="ban_{$b.id}" value="1"></td>
  <td id="{$rid}" style="text-align: left;"><a href="{$script}?action={$action}&step=3&id={$b.id}">{$b.ip}</a></td>
  <td id="{$rid}">{$b.access}</td>
  <td id="{$rid}">{$b.priority}</td>
  <td id="{$rid}">
   <a title="delete" href="{$script}?action={$action}&step=4&id={$b.id}" onclick="return rowdelete();"><img src="{tpl file=/img/admin/delete.png}"></a>
  </td>
</tr>
{/foreach}
</table>