<h3>This must be run only 1 time</h3>
{if isset($version)}
<p>This has been already RUN</p>
<a href="{$script}?action=admin">Go back to administration page</a>
{else}
<p>Running this will change the contents of the database, so be sure you have a backup just in case</p>
<p>It might take some time to upgrade, please wait for it to finish</p>
<a href="{$script}?action=adminupgrade&step=2&upgrade=true">Proceed &gt;&gt;</a>
{/if}