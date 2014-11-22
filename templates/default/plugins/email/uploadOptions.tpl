{if !isset($user.email)}
<tr><td>{tr}Send me an e-mail{/tr}:</td><td><input type="checkbox" name="emailme" value="yes"></td></tr>
<tr><td>{tr}Your e-mail address{/tr}:</td><td><input type="text" size="30" value="{$finfo.email}" name="email"></td></tr>
{else}
<tr><td>{tr}Send me an e-mail{/tr}:</td><td><input type="checkbox" checked name="emailme" value="yes"></td></tr>
{/if}
<tr><td>{tr}Send e-mail to{/tr}:</td><td><input type="text" size="30" value="{$finfo.emailto}" name="emailto"></td></tr>
<tr><td>{tr}Send remove link{/tr}:</td><td><input type="checkbox" name="removelink" value="yes"></td></tr>
<tr><td>{tr}e-mail Subject{/tr}:</td><td><input type="text" size="30" value="{$finfo.subject}" name="subject"></td></tr>
<tr><td valign="top">{tr}e-mail Message{/tr}:</td><td><textarea cols="30" rows="10" name="message">{$finfo.message}</textarea></td></tr>
