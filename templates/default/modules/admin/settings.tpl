{include file="default/modules/admin/adminmenu.tpl"}
{include file="default/modules/admin/settingsmenu.tpl"}

{tr}This are the configured settings for a review{/tr}:<br />

<table border="1">
{foreach from=$config item=c key=k}
<tr><td valign="top"><b>{$k} :</b></td><td>
{if is_array($c)}
<table border="0">
{foreach from=$c item=sc key=sk}
<tr><td>{$sk} :</td><td>{if $sk==='password'}*no display*{else}{$sc|escape}{/if}</td></tr>
{/foreach}
</table>
{else}
{if $k==='password'}*no display*{else}{$c|escape}{/if}
{/if}
</td></tr>
{/foreach}
</table>
<br /><br /><br /><br />