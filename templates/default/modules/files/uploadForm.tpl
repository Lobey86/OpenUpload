{literal}
<script>
function unhide(i) {
  obj = document.getElementById('uploadbutton');
  obj.style.visibility = 'visible';

  obj = document.getElementById('upload_'+i);
  if (obj)
    obj.style.visibility = 'visible';
}

function uploadProgress() {
  obj = document.getElementById('upload');
  if (obj) {
    var ajax = new Ajax.Updater(
	          {success: 'upload'},
              'index.php',
              {method: 'get', parameters: 'action=u&step=99&type=ajax', onFailure: reportError});
    setTimeout('uploadProgress()',1000);
  }
}
function reportError(request) {
   $F('upload') = "Error...";
}

</script>
{/literal}<br />
<div id="upload">
<form method="post" enctype='multipart/form-data' action="{$script}" name="uploadform">
<input type="hidden" name="action" value="{$action}">
<input type="hidden" name="step" value="{$nextstep}">
{if isset($user.max_upload_size)}
<input type="hidden" name="MAX_FILE_SIZE" value="{$user.max_upload_size}">
<input type="hidden" id="file_id" name="{$identifiername}" value="{$identifier}">
{/if}
{tr}Select the file to be uploaded{/tr}<br />
 <input type="file" class="file" size="60" name="upload" onchange="if (this.value!='') unhide(1);"><br />
{if isset($multiupload)}
{section name=i start=1 loop=$multiupload}
{if isset($user.max_upload_size)}
<div id="upload_{$smarty.section.i.index}" style="visibility: hidden">
<input type="hidden" name="MAX_FILE_SIZE" value="{$user.max_upload_size}">
{/if}
<input type="file" class="file" size="60" name="upload_{$smarty.section.i.index}" onchange="if (this.value!='') unhide({$smarty.section.i.index+1});"><br />
</div>
{/section}
{/if}
{if isset($user.max_upload_size)}
<div id="msg">{tr}Maximum allowed upload size{/tr}: {$user.max_upload_size|fsize_format:"MB":0}</div>
{/if}
{$plugins}
<div id="uploadbutton" style="visibility:hidden"><br />
  <a href="{$script}" onclick="setTimeout('uploadProgress()',1000); document.uploadform.submit(); return false;">
  <img src="{tpl file=/img/upload.png}" border="0"><br />
  {tr}Upload{/tr}</a>
</div>
</form>
{include file="default/modules/auth/registerlink.tpl"}
</div>
