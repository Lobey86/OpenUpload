<html>
<head>
<title>{$site.title} - {tr}{$page.title}{/tr}</title>
<meta http-equiv="Content-Type" content="text/html; charset={$charset}">
<link rel="SHORTCUT ICON" href="{tpl file=img/openupload.ico}">
<link rel="stylesheet" type="text/css" href="{tpl file=/main.css}">
<script language="javascript" type="text/javascript" src="{tpl file=/js/prototype.js}"></script>
</head>
<body>
<!-- header -->
<div id="header">
<div id="logo"><img src="{tpl file=/img/openupload.jpg}" border="0"></div>
<div id="langs">
{if count($langs)>1}
<ul>
{foreach from=$langs item=l name=c}
<li {if $smarty.foreach.c.last} style="border: 0px"{/if}><a href="{$script}?lang={$l.id}&action={$action}&step={$step}">{$l.name}</a></li>
{/foreach}
</ul>
{/if}
</div>
<div id="userinfo">
{$user.name}
</div>
<div id="title">{tr}{$page.title}{/tr}</div>
<div id="menu">
<ul>
{foreach from=$menu item=m key=k name=c}
<li {if $smarty.foreach.c.last} style="border: 0px"{/if}><a href="{$script}?action={$k}">{$m}</a></li>
{/foreach}
</ul>
</div> <!-- menu end -->
</div> <!-- header end -->
<!-- menu -->
<!-- content -->
<div id="wrapper"><br />
{foreach from=$user.messages item=m}
<div id="message">{$m}</div>
{/foreach}
{foreach from=$user.errors item=e}
<div id="error">{$e}</div>
{/foreach}
<div id="content" align="center">
{$page.content}
</div> <!-- content end -->
</div> <!-- wrapper -->
<br />&nbsp;<br />
<!-- footer -->
<div id="footer">{$site.footer}</div>
</body>
</html>