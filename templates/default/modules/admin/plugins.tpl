{include file="default/modules/admin/adminmenu.tpl"}
<br />
<center>
<table border="0" width="400" height="200">
<tr>
  <td align="center"><a href="{$script}?action=adminpluginsacl"><img src="{tpl file=/img/admin/plugins.png}" border="0" ><br />{tr}Plugins ACL{/tr}</a></td>
  <td align="center"><a href="{$script}?action=adminpluginsoptions"><img src="{tpl file=/img/admin/plugins.png}" border="0" align="center"><br />{tr}Plugins Options{/tr}</a></td>
</tr>
{$plugins}
</table>
</center>