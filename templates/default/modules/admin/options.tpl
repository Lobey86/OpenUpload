{include file="default/modules/admin/adminmenu.tpl"}
{include file="default/modules/admin/settingsmenu.tpl"}

<form method="POST" action="index.php">
<input type="hidden" name="action" value="{$action}">
<input type="hidden" name="step" value="{$step}">
<table border="0">
<tr><td>{tr}Translation module{/tr}:</td><td>
<select name="translator">
<option value="">-- {tr}Select one{/tr} --</option>
{foreach from=$tr item=t} 
  <option value="{$t}" {if $t==$config.translator}selected{/if}>{$t}</option>
{/foreach}
</select>
</td></tr>
<tr><td>{tr}Default language{/tr}:</td><td><input type="text" name="defaultlang" value="{$config.defaultlang}"></td></tr>
<tr><td>{tr}Authentication module{/tr}:<br />{tr}(LDAP Configuration needs to be done<br /> by hand for now){/tr}</td><td>
<select name="auth">
<option value="">-- {tr}Select one{/tr} --</option>
{foreach from=$auth item=t} 
  <option value="{$t}" {if $t==$config.auth}selected{/if}>{$t}</option>
{/foreach}
</select>
</td></tr>
<tr><td>{tr}Site title{/tr}:</td><td><input type="text" name="sitetitle" value="{$config.site.title}"></td></tr>
<tr><td>{tr}WebMaster E-mail{/tr}:</td><td><input type="text" name="webmaster" value="{$config.site.webmaster}"></td></tr>
<tr><td>{tr}Site E-mail{/tr}:</td><td><input type="text" name="email" value="{$config.site.email}"></td></tr>
<tr><td>{tr}Confirm registration with e-mail{/tr}:</td><td><input type="checkbox" name="confirmregistration" value="yes" 
{if ($config.registration.email_confirm=='yes')}checked{/if}></td></tr>
<tr><td>{tr}Template{/tr}:</td><td>
<select name="template">
<option value="">-- {tr}Select one{/tr} --</option>
{foreach from=$templates item=t} 
  <option value="{$t}" {if $t==$config.site.template}selected{/if}>{$t}</option>
{/foreach}
</select>
</td></tr>
<tr><td>{tr}Template Footer{/tr}:</td><td><textarea name="sitefooter" cols="50" rows="5">{$config.site.footer}</textarea></td></tr>
<tr><td>{tr}Maximum upload size (in MB){/tr}:</td><td><input type="text" name="max_upload_size" value="{$config.max_upload_size}"></td></tr>
<tr><td>{tr}Maximum download time (in Min){/tr}<br /w>{tr}0 disables it{/tr}:</td><td><input type="text" name="max_download_time" value="{$config.max_download_time}"></td></tr>
<tr><td>{tr}Max num. of file uploaded per upload{/tr}:</td><td><input type="text" name="multiupload" value="{$config.multiupload}"></td></tr>
<tr><td>{tr}Use shorter links?{/tr}:</td><td><input type="checkbox" name="use_short_links" value="yes" {if $config.use_short_links=='yes'}checked{/if} 
<tr><td>{tr}Length of IDs (suggested min 6){/tr}:</td><td><input type="text" name="id_max_length" value="{$config.id_max_length}"></td></tr>
<tr><td>{tr}Use alphanumerical IDs?{/tr}:</td><td><input type="checkbox" name="id_use_alpha" value="yes" {if $config.id_use_alpha=='yes'}checked{/if}</td></tr>
<tr><td>{tr}Allow unprotected file removal?{/tr}:</td><td><input type="checkbox" name="allow_unprotected_removal" value="yes" {if $config.allow_unprotected_removal=='yes'}checked{/if}</td></tr>
<tr><td>{tr}Upload tracking method{/tr}:</td><td><select name="progress">
{foreach from=$progress item=t} 
  <option value="{$t}" {if $t==$config.progress}selected{/if}>{$t}</option>
{/foreach}
</select></td></tr>
<tr><td>{tr}Enable activity logging?{/tr}:</td><td><input type="checkbox" name="logging" value="yes" {if $config.logging.enabled=='yes'}checked{/if} ></td></tr>
<tr><td>{tr}Database logging level{/tr}:</td><td><select name="log_db_level">
{foreach from=$loglevels item=x key=t} 
  <option value="{$t}" {if $t==$config.logging.db_level}selected{/if}>{$x}</option>
{/foreach}
</select></td></tr>
<tr><td>{tr}Syslog logging level{/tr}:</td><td><select name="log_syslog_level">
{foreach from=$loglevels item=x key=t} 
  <option value="{$t}" {if $t==$config.logging.syslog_level}selected{/if}>{$x}</option>
{/foreach}
</select></td></tr>
<TR><TD colspan="2"><input type="submit" class="submit" name="save" value="{tr}Save Changes{/tr}"> <input type="submit" class="submit" name="download" value="{tr}Download config file{/tr}"></TD></TR>
</table>
</form>
