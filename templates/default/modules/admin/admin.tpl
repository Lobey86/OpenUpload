{tr}Here you can administer{/tr}:<hr>
<table border="0" width="400" height="300">
<tr>
  <td align="center"><a href="{$script}?action=adminsettings"><img src="{tpl file=/img/admin/settings.png}" border="0" ><br />{tr}Settings{/tr}</a></td>
  <td align="center"><a href="{$script}?action=adminplugins"><img src="{tpl file=/img/admin/plugins.png}" border="0" align="center"><br />{tr}Plugins{/tr}</a></td>
  <td align="center"><a href="{$script}?action=adminfiles"><img src="{tpl file=/img/admin/files.png}" border="0" ><br />{tr}Files{/tr}</a></td>
</tr>
<tr>
  <td align="center"><a href="{$script}?action=adminusers"><img src="{tpl file=/img/admin/users.png}" border="0" ><br />{tr}Users{/tr}</a></td>
  <td align="center"><a href="{$script}?action=admingroups"><img src="{tpl file=/img/admin/groups.png}" border="0" ><br />{tr}Groups{/tr}</a></td>
  <td align="center"><a href="{$script}?action=adminrights"><img src="{tpl file=/img/admin/rights.png}" border="0" ><br />{tr}Rights{/tr}</a></td>
</tr>
<tr>
  <td align="center"><a href="{$script}?action=adminlangs"><img src="{tpl file=/img/admin/langs.png}" border="0" ><br />{tr}Languages{/tr}</a></td>
  <td align="center"><a href="{$script}?action=adminbanned"><img src="{tpl file=/img/admin/banned.png}" border="0" ><br />{tr}Banned IPs{/tr}</a></td>
  <td align="center"><a href="{$script}?action=adminlogs"><img src="{tpl file=/img/admin/log.png}" border="0" ><br />{tr}Logs / Statistics{/tr}</td>
</tr>
{if isset($upgrade)}
<a href="{$script}?action=adminupgrade">Upgrade to 0.4.2</a>
{/if}
{$plugins}
</table>