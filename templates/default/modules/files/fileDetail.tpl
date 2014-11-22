<center>
<table border="0">
  <tr><td>{tr}ID{/tr}:</td><td><b>{$finfo.id}</b></td></tr>
  <tr><td>{tr}File description{/tr}:</td><td><b>{$finfo.description}</b></td></tr>
  <tr><td>{tr}Date{/tr}:</td><td><b>{$finfo.upload_date}</b></td></tr>
{foreach from=$files item=f key=k}
  <tr><td>{tr}File name{/tr}:</td><td><b>{$f.name}</b></td></tr>
  <tr><td>{tr}File size{/tr}:</td><td><b>{$f.size|fsize_format}</b></td></tr>
{/foreach}
{$plugins}
</table>
</center>
<hr />
<table border="0">
<tr><td>{tr}Download link{/tr}:</td>
<td><a href="{$finfo.downloadlink}">{$finfo.downloadlink}</a>
  </td></tr>
{if $finfo.removelink!=''}
<tr><td>{tr}Remove link{/tr}:</td><td>
<a href="{$finfo.removelink}">{$finfo.removelink}</a></td></tr>
{/if}
</table>