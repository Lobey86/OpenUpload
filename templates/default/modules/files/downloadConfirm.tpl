{tr}You can now proceed downloading the file{/tr}:
<table border="0">
<tr><td>{tr}File description{/tr}:</td><td><b>{$finfo.description}</b></td></tr>
<tr><td>{tr}Uploaded on{/tr}:</td><td><b>{$finfo.upload_date}</b></td></tr>
{$plugins}
{foreach from=$files item=f key=k}
<tr><td>{tr}File name{/tr}:</td><td><b>{$f.name}</b></td></tr>
<tr><td>{tr}File size{/tr}:</td><td><b>{$f.size|fsize_format}</b></td></tr>
<td colspan="2" align="center"><a href="{$script}?action=g&fid={$k}">
  <img src="{tpl file=/img/download.png}" border="0"><br />
  {tr}Download file{/tr}</a></td></tr>
{/foreach}
</table>

