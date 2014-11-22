<table border="0" width="300">
<tr><td>{tr}Login name{/tr}:</td><td>{$puser.login}</td></tr>
<tr><td>{tr}Full Name{/tr}:</td><td>{$puser.name}</td></tr>
<tr><td>{tr}e-mail{/tr}:</td><td>{$puser.email}</td></tr>
<tr><td>{tr}Language{/tr}:</td><td>{$langs[$puser.lang].name}</td></tr>
<tr><td colspan="2"><hr></td></tr>
<tr><td colspan="2"><a href="{$script}?action={$action}&step={$nextstep}">{tr}Change{/tr}</td></tr>
</table>