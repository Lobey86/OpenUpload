{if !isset($user.login)}
  {if $register}
{tr}You don't have an account?{/tr} <a href="{$script}?action=register">{tr}Register here{/tr}</a>
 {tr}or you can{/tr} <a href="{$script}?action=login">{tr}Login here{/tr}.</a>
  {/if}
{/if}
