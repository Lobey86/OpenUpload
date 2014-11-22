{if count($upgradeerrors)>0}
<h3>Upgrade completed with the following errors:</h3>
<p>
{foreach from=$upgradeerrors item=e}
{$e}<br />
{/foreach}
</p>
{else}
<h3>Upgrade completed successfully</h3>
<a href="{$script}?action=admin">Back to Admin</a>
{/if}

