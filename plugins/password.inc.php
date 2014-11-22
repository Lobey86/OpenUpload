<?php

class PasswordPlugin extends OpenUploadPlugin {

  function PasswordPlugin() {
    $this->fields = array('password');
    $this->description = tr('Option to add password protection for file download');
  }

  function uploadOptions(&$finfo,$acl) {
    if ($acl!='enable') return true;
    if (isset(app()->plugins['email']) and
         isset(app()->pluginAcl['email']) and
         app()->pluginAcl['email']['access']=='enable') {
      app()->tpl->assign('emailplugin',true);
    } else {
      app()->tpl->assign('emailplugin',false);
    }
    $this->display('uploadOptions');
    return true;
  }

  function uploadConfirm(&$finfo,$acl) {
    global $_POST;

    if (isset($_POST['protect']) and $acl=='enable') {
      $finfo[0]['password'] = crypt($_POST['protect']);
      if (isset($_POST['protect_notify'])) { /* want the e-mail to include the password? */
        $finfo[0]['plainpassword'] = $_POST['protect'];
      } else {
        $finfo[0]['plainpassword']='';
      }
    } else {
      $finfo[0]['password'] = crypt("");
      $finfo[0]['plainpassword'] = "";
    }
    return true;
  }

  function downloadRequest($finfo,$acl) {
    if (($finfo[0]['password']!='') and ($finfo[0]['password']!=crypt("",$finfo[0]['password'])) ) {
      $this->display('downloadRequest');
      return false;
    } 
    return true;
  }

  function downloadConfirm($finfo,$acl) {
    global $_POST;

    if (($finfo[0]['password']!='') and ($finfo[0]['password']!=crypt("",$finfo[0]['password'])) ) {
      $result = $finfo[0]['password']==crypt($_POST['protect'],$finfo[0]['password']);
      if (!$result) app()->error(tr('Wrong password!'));
      return $result;
    } else {
      return true;
    }
  }

  function removeRequest($finfo,$acl) {
    return $this->downloadRequest($finfo,$acl);
  }

  function removeConfirm($finfo,$acl) {
    return $this->downloadConfirm($finfo,$acl);
  }

  function fileDetail(&$finfo,$acl) {
    if (($finfo[0]['password']!='') and ($finfo[0]['password']!=crypt("",$finfo[0]['password'])) )
      $this->display('fileDetail');
    return true;
  }
}

?>