<?php

class emailPlugin extends OpenUploadPlugin {

  function emailPlugin() {
    $this->description = tr('Add option to send e-mail to the uploader or to another e-mail address');
    $this->options = array(
      array('name' => 'multirecipients', 'description' => tr('allow multiple recipients (1 = enable)'), 'type' => 'text'),
    );

  }

  function uploadOptions(&$finfo,$acl) {
    if ($acl!='enable') return true;
    $this->display('uploadOptions');
    return true;
  }

  function uploadConfirm(&$finfo,$acl) {
    global $_POST;
    if ($acl!='enable') return true;
    /* do e-mail checking and so */
/*    $this->display('upload'); */
    $group = $this->getGroup('maxrecipients');
    $finfo[0]['emailme']=$_POST['emailme'];
    $finfo[0]['emailfrom']=app()->config['site']['email'];
    $finfo[0]['emailto']=$_POST['emailto'];
    $finfo[0]['email_removelink']=$_POST['removelink'];
    $finfo[0]['subject']=htmlentities($_POST['subject']);
    $finfo[0]['message']=htmlentities($_POST['message']);
// TODO: Fix this mess with the e-mail sender
    if ($_SESSION['user']['email']=='') {
      if ($finfo[0]['emailme']=="yes") {
        /* check valid e-mail */
        if (!validEmail($_POST['email'])) {
          app()->error(tr('Your e-mail address isn\'t valid!'));
          return false;
        }
        $finfo[0]['emailfrom']=$_POST['email'];
      }
    } else {
      $finfo[0]['emailfrom']=$_SESSION['user']['email'];
    }
    if (!isset($this->config['multirecipients'][$group]) and isset($this->config['multirecipients']['*'])) {
      $this->config['multirecipients'][$group]=$this->config['multirecipients']['*'];
    }
    if ($finfo[0]['emailto']!='') {
      if ($this->config['multirecipients'][$group]=='1') {
        $emailto = split(';',$_POST['emailto']);
      } else {
        $emailto[0] = $_POST['emailto']; 
      }
      foreach ($emailto as $destination) {
        if (!validEmail(trim($destination))) {
          app()->error(tr('Destination e-mail address "%1" isn\'t valid!',$destination));
          return false;
        }
      }
    }

    return true;
  }

  function uploadFileInfo(&$finfo,$acl) {
    global $_SESSION;

    if ($acl!='enable') return true;
    /* send the e-mails */
    app()->tpl->assign('finfo',$finfo);
    if ($finfo[0]['emailme']=="yes") {
      app()->tpl->assign('remove','yes');
      $subject = app()->config['site']['title'].': '.tr("Information about your uploaded file: %1",$finfo[0]['description']);
      sendMail(app()->config['site']['email'],'noreply',$finfo[0]['emailfrom'],$subject,'plugins/email/notify');
    }
    if ($finfo[0]['emailto']!='') {
      $subject = $finfo[0]['subject']!=''?$finfo[0]['subject']:tr("An upload was delivered to you"); 
      $subject = app()->config['site']['title'].': '.$subject;
      app()->tpl->assign('remove',$finfo[0]['email_removelink']);
      $emails = split(';',$finfo[0]['emailto']);
      foreach ($emails as $emailto) {
        sendMail($finfo[0]['emailfrom'],$finfo[0]['emailfrom'],trim($emailto),$subject,'plugins/email/notify');
      }
    }
    /* don't send it twice */
    $finfo[0]['emailme']=='';
    $finfo[0]['emailto']=='';
    return true;
  }

  function fileDetail(&$finfo,$acl) {
    global $_GET;
    if ($acl!='enable') return true;

    if (isset($_GET['emailme'])) {
      app()->tpl->assign('remove','yes');
      $subject = app()->config['site']['title'].': '.tr("Information about your uploaded file: %1",$finfo[0]['description']);
      app()->tpl->assign('finfo',$finfo);
      sendMail(app()->config['site']['email'],'noreply',$_SESSION['user']['email'],$subject,'plugins/email/notify');
      app()->message(tr('E-mail was sent!'));
      redirect('?action=l&step=2&id='.$_GET['id']);
      return false;
    } else if (isset($_GET['sendemail']) and isset($_GET['emailto']) and ($_GET['emailto']!='')) {
      $subject = $_GET['subject']!=''?$_GET['subject']:tr("An upload was delivered to you"); 
      $subject = app()->config['site']['title'].': '.$subject;
      $finfo[0]['subject']=$_GET['subject'];
      $finfo[0]['message']=$_GET['message']; 
      app()->tpl->assign('finfo',$finfo);
      app()->tpl->assign('remove',$_GET['removelink']);
      $emails = split(';',$_GET['emailto']);
      foreach ($emails as $emailto) {
        sendMail($_SESSION['user']['email'],$_SESSION['user']['email'],trim($emailto),$subject,'plugins/email/notify');
      }
      app()->message(tr('E-mail was sent to: %1!',$_GET['emailto']));
      redirect('?action=l&step=2&id='.$_GET['id']);
      return false;
    } else {
      app()->tpl->assign('finfo',$finfo); 
      $this->display('fileDetail');
      return true;
    }
  }
}
