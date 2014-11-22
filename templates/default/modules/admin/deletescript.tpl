{literal}
function multidelete() {
  if (window.confirm(msg1)) 
    document.getElementById('deleteform').submit(); 
  return false;
}

function rowdelete() {
  return window.confirm(msg2);
}
{/literal}
