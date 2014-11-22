{include file="default/modules/admin/adminmenu.tpl"}
<br />
<table border="0" id="dbtable">
<tr>
  <th width="100">{tr}Plugin{/tr}</th>
  <th width="400">{tr}Description{/tr}</th>
</tr>
{foreach from=$pluginlist item=p}
{cycle values="row1,row2" advance=true assign=rid}
<tr>
  <td id="{$rid}" style="text-align:left; vertical-align: top;">
     <a href="{$script}?action={$action}&step=2&id={$p.name}">{$p.name}</a></td>
  <td id="{$rid}" style="text-align:justify">{$p.description}</td>
</tr>
{/foreach}
</table>