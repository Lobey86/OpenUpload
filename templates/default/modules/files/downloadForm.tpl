{tr}Please enter the File Information requested{/tr}:
<form action="{$script}" method="POST">
<input type="hidden" name="action" value="{$action}">
<input type="hidden" name="step" value="{$nextstep}">
<table border="0">
<table border="0">
<tr><td>{tr}File code{/tr}:</td><td><input id="id" type="text" size="30" name="id"></td></tr>
{$plugins}
<tr><td colspan="2" align="right"><input class="submit" type="submit" value="{tr}Proceed{/tr}"></td></tr>
</table>
{literal}
<script>
document.getElementById('description').focus();
</script>
{/literal}