This is a multi-part message MIME format.

--{$boudary}
Content-Type: text/plain; charset="iso-8559-1"
Content-Transfer-Encoding: 7bit

{tr}Dear {/tr} {$reguser.name},
{tr}This e-mail message is sent to you to confirm your account registration has a valid e-mail address.{/tr}


{tr}Open the following link in a browser to confirm your account.{/tr}:

{$reglink}

{tr}Best regards{/tr}.

{if isset($adminemail)}
-------------------
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

</style>
{/literal}
</header>
<body>
<p>{tr}Dear {/tr} {$reguser.name},<br />
{tr}This e-mail message is sent to you to confirm your account registration has a valid e-mail address.{/tr}</p>


<p>{tr}Open the following link in a browser to confirm your account.{/tr}:<br />
<a href="{$reglink}">{$reglink}</a></p>

<p>{tr}Best regards{/tr}.</p>

{if isset($adminemail)}
<hr>
<p>{tr}For complains please send an email to{/tr}: <a href="mailto:{$adminemail}">{$adminemail}</a></p>
{/if}
<div id="footer">OpenUpload &copy; by Alessandro Briosi 
<a href="http://openupload.sourceforge.net>http://openupload.sourceforge.net</a></div>
</body>
</html>