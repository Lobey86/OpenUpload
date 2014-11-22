{if isset($user.email)}
<form method="GET" action="{$script}">
<tr><td>{tr}Send me an e-mail{/tr}:</td><td>
<input type="hidden" name="action" value="{$action}">
<input type="hidden" name="step" value="{$step}">
<input type="hidden" name="id" value="{$finfo[0].id}">
<input type="submit" name="emailme" value="{tr}Send{/tr}">
</td></tr>
{/if}
<tr><td>{tr}Send e-mail to{/tr}:</td><td><input type="text" size="30" value="{$finfo.emailto}" name="emailto"></td></tr>
<tr><td>{tr}Send remove link{/tr}:</td><td><input type="checkbox" name="removelink" value="yes"></td></tr>
<tr><td>{tr}e-mail Subject{/tr}:</td><td><input type="text" size="30" value="{$finfo.subject}" name="subject"></td></tr>
<tr><td valign="top">{tr}e-mail Message{/tr}:</td><td><textarea cols="30" rows="5" name="message">{$finfo.message}</textarea></td></tr>
<tr><td>&nbsp;</td><td><input type="submit" name="sendemail" value="{tr}Send{/tr}"></td></tr>
</form>
