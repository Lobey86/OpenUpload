<?php

class CaptchaPlugin extends OpenUploadPlugin {

  function CaptchaPlugin() {
    $this->fields = array('captcha');
    $this->description = tr('Add captcha protection to file download and user registration');
  }

  function uploadOptions(&$finfo,$acl) {
    if ($acl!='enable') return true;
    $this->display('uploadOptions');
    return true;
  }

  function uploadConfirm(&$finfo,$acl) {
    global $_POST;

    if ($acl!='enable') return true;
    $finfo[0]['captcha'] = $_POST['captcha'];
    return true;
  }

  function downloadRequest($finfo,$acl) {
    if ($finfo[0]['captcha']==1) {
      $this->assign('captcha_img',app()->config['WWW_ROOT'].'/plugins/captcha.php');
      $this->display('downloadRequest');
      return false;
    }
    return true;
  }

  function downloadConfirm($finfo,$acl) {
    global $_POST;

    if ($finfo[0]['captcha']==1) {
      require_once(app()->config['INSTALL_ROOT'].'/plugins/securimage/securimage.php');
      $securimage = new Securimage();
      $result = $securimage->check($_POST['captcha_code']);
      if (!$result) app()->error(tr('Wrong captcha code! please try again.'));
      return $result;
    } else 
      return true;
  }

  function registerForm($uinfo,$acl) {
    $this->assign('captcha_img',app()->config['WWW_ROOT'].'/plugins/captcha.php');
    $this->display('registerForm');
    return true;
  }

  function registerConfirm(&$uinfo,$acl) {
    global $_POST;

    require_once(app()->config['INSTALL_ROOT'].'/plugins/securimage/securimage.php');
    $securimage = new Securimage();
    $result = $securimage->check($_POST['captcha_code']);
    if (!$result) app()->error(tr('Wrong captcha code! please try again.'));
    return $result;
  }

  function removeRequest($finfo,$acl) {
    if ($finfo[0]['captcha']==1) {
      $this->assign('captcha_img',app()->config['WWW_ROOT'].'/plugins/captcha.php');
      $this->display('removeRequest');
      return false;
    }
    return true;
  }

  function removeConfirm($finfo,$acl) {
    return $this->downloadConfirm($finfo,$acl);
  }

  function fileDetail(&$finfo,$acl) {
     if ($finfo[0]['captcha']!='')
      $this->display('fileDetail');
    return true;
  }
}

?>