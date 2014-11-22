<center><img src="{tpl file=/img/wait.gif}"> {tr}Uploading{/tr}: 
{if $progress.complete>0}{$progress.complete|fsize_format} of {$progress.total|fsize_format} <b>({$progress.percentage}%)</b>
{else}
{tr}please wait ...{/tr}
{/if}</center>