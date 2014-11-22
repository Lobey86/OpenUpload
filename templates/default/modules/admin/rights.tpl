{include file="default/modules/admin/adminmenu.tpl"}
<br />
<table border="0" id="dbtable">
<tr>
  <th width="100">{tr}Group{/tr}</th>
  <th width="200">{tr}Description{/tr}</th>
  <th width="100">{tr}Rights set?{/tr}</th>
</tr>
{foreach from=$groups item=g}
{cycle values="row1,row2" advance=true assign=rid}
<tr>
  <td id="{$rid}" style="text-align:left"><a href="{$script}?action=adminrights&step=2&id={$g.name}">{if $g.name=='*'}[{tr}Any{/tr}]{else}{$g.name}{/if}</a></td>
  <td id="{$rid}">{$g.description}</td>
  <td id="{$rid}">{if isset($rights[$g.name])}{tr}Yes{/tr}{else}{tr}No{/tr}{/if}</td>
</tr>
{/foreach}
</table>
