{include file="default/modules/admin/adminmenu.tpl"}
<br />
<div id="filter">
<form action="index.php" id="filterform" method="GET">
<input type="hidden" name="action" value="{$action}">
<input type="hidden" name="step" value="{$step}">
<input type="hidden" name="page" value="1">
{tr}Filter{/tr}:<select name="level" onchange="document.getElementById('filterform').submit()">
<option value="" {if $level==""}selected{/if}>-- {tr}All{/tr} --</option>
<option value="error" {if $level=="error"}selected{/if}>{tr}Errors{/tr}</option>
<option value="security" {if $level=="security"}selected{/if}>{tr}Security{/tr}</option>
<option value="warning" {if $level=="warning"}selected{/if}>{tr}Warnings{/tr}</option>
<option value="notice" {if $level=="notice"}selected{/if}>{tr}Notice{/tr}</option>
<option value="info" {if $level=="info"}selected{/if}>{tr}Info{/tr}</option>
</select>
</form>
</div>
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
<table border="0" id="dbtable">
<tr>
  <th width="100">{tr}Date{/tr}</th>
  <th width="100">{tr}Type{/tr}</th>
  <th width="100">{tr}User{/tr}</th>
  <th width="100">{tr}Module{/tr}</th>
  <th width="100">{tr}Action{/tr}</th>
  <th width="100">{tr}Real Action{/tr}</th>
  <th width="100">{tr}Plugin{/tr}</th>
  <th width="100">{tr}Result{/tr}</th>
  <th width="200">{tr}Additional Info{/tr}</th>
</tr>
{foreach from=$logs item=l}
{cycle values="row1,row2" advance=true assign=rid}
<tr>
  <td id="{$rid}">{$l.log_time}</td>
  <td id="{$rid}">{$l.level}</td>
  <td id="{$rid}">{$l.user_login}</td>
  <td id="{$rid}">{$l.module}</td>
  <td id="{$rid}">{$l.action}</td>
  <td id="{$rid}">{$l.realaction}</td>
  <td id="{$rid}">{$l.plugin}</td>
  <td id="{$rid}">{$l.result}</td>
  <td id="{$rid}">{$l.moreinfo}</td>
</tr>
{/foreach}
</table>
