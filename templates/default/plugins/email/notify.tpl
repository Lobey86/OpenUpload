This is a multi-part message MIME format.

--{$boudary}
Content-Type: text/plain; charset="iso-8559-1"
Content-Transfer-Encoding: 7bit

{tr}You are receiving this message because someone uploaded a file on our OpenUpload server for you.{/tr}

{tr}Description{/tr}: {$finfo[0].description}
{if $finfo[0].plainpassword!=''}
{tr}Password{/tr}:    {$finfo[0].plainpassword}
{/if}
{if ($finfo[0].message!="")}
{tr}User message{/tr}:
{$finfo[0].message}
{/if}

{tr}To download the file open the following link in a browser{/tr}

{$finfo[0].downloadlink}

{if isset($remove)}
{tr}To remove the file from our server open the following link in a browser{/tr}

{$finfo[0].removelink}
{/if}

{if isset($adminemail)}
{tr}For complains please send an email to{/tr}: {$adminemail}
{/if}

-------------------------------------------------------------------
OpenUpload c by Alessandro Briosi http://openupload.sourceforge.net

--{$boudary}
Content-Type: text/html; charset="iso-8559-1"
Content-Transfer-Encoding: 7bit

<html>
<header>
<title>{$subject}</title>
<style>
{literal}
body {
  font-family: Arial, Helvetica, Tahoma;
  font-size: 10pt;
}
a {
  color: #3161cf;
  font-weight: bold;
  font-size: 11pt;
  text-decoration: none;
}
a:visited {
  color: #3161cf;
  font-weight: bold;
  font-size: 11pt;
  text-decoration: none;
}
a:hover {
  color: #4c8dff;
  font-weight: bold;
  font-size: 11pt;
  text-decoration: none;
}
#footer {
  clear: both;
  position: fixed;
  bottom: 0px;
  height: 20px;
  width: 100%;
  font-weight: bold;
  font-size: 9pt;
  border-top: 1px solid #000000;
  text-align: center;
}
td {
  font-size: 10pt;
  font-weight: bold;
}
</style>
{/literal}
</header>
<body>
<p>{tr}You are receiving this message because someone uploaded a file on our OpenUpload server for you.{/tr}</p>

<table border="0">
<tr><td>{tr}Description{/tr}:</td><td>{$finfo[0].description}</td></tr>
{if $finfo[0].plainpassword!=''}
<tr><td>{tr}Password{/tr}:</td><td>{$finfo[0].plainpassword}</td></tr>
{/if}
</table>

{if ($finfo[0].message!="")}
<p>{tr}User message{/tr}<br />:
{$finfo[0].message}
</p>
{/if}

<p>{tr}To download the file open the following link in a browser{/tr}<br />
<a href="{$finfo[0].downloadlink}">{$finfo[0].downloadlink}</a></p>

{if isset($remove)}
<p>{tr}To remove the file from our server open the following link in a browser{/tr}<br />
<a href="{$finfo[0].removelink}">{$finfo[0].removelink}</a>
{/if}

{if isset($adminemail)}
<p>{tr}For complains please send an email to{/tr}: <a href="mailto:{$adminemail}">{$adminemail}</a></p>
{/if}
<div id="footer"><a href="http://openupload.sourceforge.net>OpenUpload</a> &copy; by Alessandro Briosi</div>
</body>
</html>