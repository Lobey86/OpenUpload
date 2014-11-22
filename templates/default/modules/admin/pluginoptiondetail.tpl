{include file="default/modules/admin/adminmenu.tpl"}
<h3>{$pluginname}</h3>

{if count($options)==0}
<div id="message">{tr}Plugin does not have any option to be configured{/tr}</div>
{else}
<div id="toolbar">
<a href="{$script}?action={$action}&step=3&id={$pluginname}"><img src="{tpl file=/img/admin/tadd.png}"></a>
<img src="{tpl file=/img/admin/tdelete.png}">
</div>
<br />
<table border="0" id="dbtable">
  <tr>
    <th width="150">{tr}Group{/tr}</th>
{foreach from=$options item=o}
    <th width="200">{$o.description}</th>
{/foreach}
    <th width="80">{tr}Actions{/tr}</th>
  </tr>
{foreach from=$groups item=g}
  {cycle values="row1,row2" advance=true assign=rid}
  {if isset($plugin_options[$g.name])}
  <tr>
    <td id="{$rid}" style="text-align:left; vertical-align: top;">
     <a href="{$script}?action={$action}&step=4&id={$pluginname}&gid={$g.name}">{$g.name}</a></td>
    {foreach from=$options item=o}
    <td id="{$rid}" style="text-align:left; vertical-align: top;"><pre>{$plugin_options[$g.name][$o.name].value}</pre></td>
    {/foreach}
    <td id="{$rid}" style="text-align:justify">
    <a href="{$script}?action={$action}&step=4&id={$pluginname}&gid={$g.name}"><img src="{tpl file=/img/admin/edit_plugin.png}"></a>
   &nbsp; 
   <a href="{$script}?action={$action}&step=5&id={$pluginname}&gid={$g.name}"><img src="{tpl file=/img/admin/delete.png}"></a></td>
    </td>
  </tr>
  {/if}
{/foreach}
</table>
{/if}