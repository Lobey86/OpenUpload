<div id="login">
<fieldset>
<legend>{tr}User login{/tr}</legend>
<form action="{$script}" method="POST">
<input type="hidden" name="action" value="{$action}">
<input type="hidden" name="step" value="{$nextstep}">
<table border="0">
<tr><td>{tr}User name{/tr}:</td><td><input type="text" id="username" name="username" value="{$username}"></td></tr>
<tr><td>{tr}Password{/tr}:</td><td><input type="password" name="pwd"></td></tr>
<tr><td colspan="2" align="right"><input class="submit" type="submit" value="{tr}Login{/tr}"></td></tr>
</table>
</form>
</fieldset>
</div>
{include file="default/modules/auth/registerlink.tpl"}
{literal}
<script langiage="javascript">
  obj = document.getElementById('username');
  obj.focus();
</script>
{/literal}
