<div id="mime">{tr}Only the following mime types are allowed{/tr} (
{if $message!=''}{$message}
{else}{foreach from=$mimetypes item=m}{$m}, {/foreach}{/if}
)</div>