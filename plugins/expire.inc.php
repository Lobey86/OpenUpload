<?php

class expirePlugin extends OpenUploadPlugin {

  function expirePlugin() {
    $this->description = tr('Maximum number of days an upload will be kept on the server.');
    $this->options = array(
      array('name' => 'days', 'description' => tr('N. Of Days'), 'type' => 'text'),
    );
    $this->fields = array('expire');
  }

  function uploadForm(&$finfo,$acl) {
    if ($acl!='enable') return true;
    $group = $this->getGroup('days');
    /* now set */
    if (!isset($this->config['days'][$group]) and isset($this->config['days']['*'])>0) {
      $this->config['days'][$group]=$this->config['days']['*'];
    }
    if ($this->config['days'][$group]>0) {
      $this->assign('msg',tr('Files will be kept on our server for %1 days',$this->config['days'][$group]));
      $this->display('uploadForm');
    }
    return true;
  }

  function calculateExpireDate() {

    $group = $this->getGroup('days');
    /* now set */
    if (!isset($this->config['days'][$group]) and isset($this->config['days']['*'])>0) {
      $this->config['days'][$group]=$this->config['days']['*'];
    }
    if ($this->config['days'][$group]>0) {
       $expire=date('Y-m-d',time()+($this->config['days'][$group]*24*60*60));
    } else {
       $expire='9999-12-31';
    }
    return $expire;
  }

  function uploadOptions(&$finfo,$acl) {
    if ($acl!='enable') return true;

    $expire = $this->calculateExpireDate();

    if ($expire!='') {
      if ($expire == '9999-12-31') 
        app()->tpl->assign('expire',tr('Never'));
      else
        app()->tpl->assign('expire',$expire);
      $this->display('uploadOptions');
    }
    return true;
  }

  function uploadConfirm(&$finfo,$acl) {
    if ($acl!='enable') return true;
    $finfo[0]['expire']=$this->calculateExpireDate();
    return true;
  }


  function fileDetail(&$finfo, $acl) {
    if ($acl != 'enable') return true;

    if ($finfo[0]['expire']!='') {
       app()->tpl->assign('expire',$finfo[0]['expire']);
       $this->display('fileDetail');
    }
    return true;
  }
}
?>