{include file="default/modules/admin/adminmenu.tpl"}
<div id="message" style="color: #ffaa00; font-size: 11pt; font-weight: bold;">{tr}PLEASE BE CAREFULL WHEN MODIFING THE RIGHTS!{/tr}</div><br />
<div id="message" style="color: #000000; font-size: 12pt; font-weight: bold;">{tr}Editing rights for group{/tr}: 
{if $group=='*'}[{tr}Any{/tr}]{else}{$group}{/if}</div>
<br />
<form action="{$script}" method="POST">
<input type="hidden" name="action" value="{$action}">
<input type="hidden" name="step" value="{$step}">
<input type="hidden" name="id" value="{$group}">
<table border="0" id="dbtable">
<tr>
  <th>{tr}Module{/tr}</th>
  <th>{tr}Action{/tr}</th>
  <th>{tr}Right{/tr}</th>
  <th>{tr}Resulting ACL{/tr}</th>
  <th>{tr}From ACL{/tr}</th>
</tr>
{foreach from=$modules key=mk item=m}
   {cycle values="row1,row2" advance=false assign=rid1}
<tr>
  <td id="{$rid1}" width="150" style="vertical-align: top;" rowspan="{$m.actions|@count}">{$m.name}</td>
 {foreach from=$m.actions key=ak item=ai}
   {cycle values="row1,row2" advance=true assign=rid2}
  <td width="200" id="{$rid2}" style="text-align: left;  vertical-align: top;">
    {if $ak=='*'}[{tr}default{/tr}]{else}{$ak}{/if}
  </td>
  <td id="{$rid2}" style="text-align: left;">
     <select name="right_{$mk}_{$ak}">
       {html_options options=$access selected=$rights[$group][$mk][$ak].access}</select>
  </td>
  <td width="100" id="{$rid2}" style="text-align: center;  vertical-align: top;">{$rights[$group][$mk][$ak].result}
  </td>
  <td id="{$rid2}" style="text-align: left;">
     {tr}Group{/tr}: {$rights[$group][$mk][$ak].comb.group} |
     {tr}Module{/tr}: {$rights[$group][$mk][$ak].comb.module} |
     {tr}Action{/tr}: {$rights[$group][$mk][$ak].comb.action}
  </td>
</tr>
  {/foreach}
{/foreach}
</table><br />
<input type="button" class="submit" value="{tr}<< Back{/tr}" onclick="document.location='{$script}?action={$action}';">
<input type="submit" class="submit" value="{tr}Apply changes{/tr}">
</form>
