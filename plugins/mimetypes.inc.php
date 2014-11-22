<?php

class MimeTypesPlugin extends OpenUploadPlugin {

  function MimeTypesPlugin() {
    $this->description = tr('Limit the mimetypes a user can upload');
    $this->options = array(
      array('name' => 'allowed', 'description' => tr('Allowed mime types'), 'type' => 'list'),
      array('name' => 'message', 'description' => tr('Types in message'), 'type' => 'text'),
    );
    /* load the plugin configuration */
  }

  function uploadForm(&$finfo,$acl) {
    if ($acl!='enable') return true;
    $group = $this->getGroup('allowed');
    if (count($this->config['allowed'][$group])==0 and count($this->config['allowed']['*'])>0) {
      $this->config['allowed'][$group]=$this->config['allowed']['*'];
      $this->config['message'][$group]=$this->config['message']['*'];
    }
    if (count($this->config['allowed'][$group])==0) {
      app()->error(tr('WARNING: no mime types defined. Plugin has been disabled!'));
    } else {
      $this->assign('message',$this->config['message'][$group]);
      $this->assign('mimetypes',$this->config['allowed'][$group]);
      $this->display('uploadForm');
    }
    return true;
  }

  function uploadComplete(&$finfo,$acl) {
    if ($acl!='enable') return true;
    $group = $this->getGroup('allowed');
    if (count($this->config['allowed'][$group])==0 and count($this->config['allowed']['*'])>0) {
      $this->config['allowed'][$group]=$this->config['allowed']['*'];
      $this->config['message'][$group]=$this->config['message']['*'];
    }
    if (count($this->config['allowed'][$group])==0) {
      app()->error(tr('WARNING: no mime types defined. Plugin has been disabled!'));
    } else {
      $result = true;
      foreach ($finfo as $f) {
        if (array_search($f['mime'],$this->config['allowed'][$group])===FALSE) {
          app()->error(tr('This file type (%1) is not allowed on this site!',$f['mime']));
          $result = false;
        }
      }
      return $result;
    }
    return true;
  }
}
?>