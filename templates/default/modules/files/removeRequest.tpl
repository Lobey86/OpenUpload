{tr}The file you requested the removal needs some input before you can proceed{/tr}: 
<form action="{$script}" method="POST" name="removeform">
<input type="hidden" name="action" value="{$action}">
<input type="hidden" name="step" value="{$nextstep}">
<table border="0">
{$plugins}
<tr><td colspan="2" align="right"><input type="submit" class="submit" value="{tr}Proceed{/tr}">
</table>
</form>
