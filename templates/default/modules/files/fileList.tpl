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
<table border="0" id="dbtable">
<tr>
  <th width="150">{tr}ID{/tr}</th>
  <th width="150">{tr}File name{/tr}</th>
  <th width="200">{tr}Description{/tr}</th>
  <th width="140">{tr}Date{/tr}</th>
  <th width="80">{tr}File size{/tr}</th>
</tr>
{foreach from=$files item=f}
<tr>
{if strpos($f.id,'_')===FALSE}
  {cycle advance=true values="row1,row2" assign=rid}
  <td id="{$rid}" style="text-align:left"><a href="{$script}?action=l&step={$nextstep}&id={$f.id}">{$f.id}</a></td>
  <td id="{$rid}" style="text-align:left">{$f.name}</td>
  <td id="{$rid}" style="text-align:left">{$f.description}</td>
  <td id="{$rid}">{$f.upload_date}</td>
  <td id="{$rid}" style="text-align:right">{$f.size|fsize_format}</td>
{else}
  <td id="{$rid}">&nbsp;</td>
  <td id="{$rid}" style="text-align:left">&nbsp;&nbsp;{$f.name}</td>
  <td id="{$rid}">&nbsp;</td>
  <td id="{$rid}">&nbsp;</td>
  <td id="{$rid}" style="text-align:right">{$f.size|fsize_format}</td>
{/if}
</tr>
{/foreach}
</table>