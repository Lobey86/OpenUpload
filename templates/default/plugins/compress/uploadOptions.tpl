{if count($compress)>0}
<tr><td>{tr}Compress the files{/tr}:</td><td>
  <select name="compress"><option value="">{tr}No compression{/tr}
  {foreach from=$compress item=c key=k}
    <option value="{$k}">{$c}</option>
  {/foreach}
  </select>
</td></tr>
{/if}