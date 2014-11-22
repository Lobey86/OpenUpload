{include file="default/modules/admin/adminmenu.tpl"}
{include file="default/modules/admin/filesmenu.tpl"}
<script>
msg1 = '{tr}Are you sure you want to delete the selected files?{/tr}';
msg2 = '{tr}Are you sure you want to delete the selected file?{/tr}';
{include file="default/modules/admin/deletescript.tpl"}
</script>
<div id="toolbar">
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
<input type="hidden" name="step" value="4">
<table border="0" id="dbtable">
<tr>
  <th width="10">{tr}S{/tr}</th>
  <th width="50">{tr}Id{/tr}</th>
  <th width="200">{tr}Name{/tr}</th>
  <th width="100">{tr}User{/tr}</th>
  <th width="100">{tr}IP{/tr}</th>
  <th width="200">{tr}Upload Date{/tr}</th>
  <th width="100">{tr}Actions{/tr}</th>
</tr>
{foreach from=$files item=f}
{cycle values="row1,row2" advance=true assign=rid}
<tr>
  <td id="{$rid}"><input type="checkbox" name="file_{$f.id}" value="1"></td>
  <td id="{$rid}">{$f.id}</td>
  <td id="{$rid}">{$f.name}</td>
  <td id="{$rid}">{$f.user_login}</td>
  <td id="{$rid}" style="text-align: left"><a title="ban IP {$f.ip}" href="{$script}?action=adminbanned&step=2&ip={$f.ip}&newaction={$action}">
                              <img align="right" src="{tpl file=/img/admin/ban.png}" ></a>{$f.ip} </td>
  <td id="{$rid}">{$f.upload_date}</td>
  <td id="{$rid}">
   <a title="delete" href="{$script}?action={$action}&step=2&id={$f.id}"  onclick="return rowdelete();"><img src="{tpl file=/img/admin/delete.png}"></a></td>
</tr>
{/foreach}
</table>