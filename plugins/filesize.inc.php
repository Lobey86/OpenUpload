<?php

/* handles different file size upload per group */

class filesizePlugin extends OpenUploadPlugin {

  function filesizePlugin() {
    $this->description = tr('Limit the maximum size of a uploaded file');
    $this->options = array(
      array('name' => 'maxsize', 'description' => tr('Maximum File Size'), 'type' => 'text'),
    );
  }

  function init() {
    /* check if it's enabled */
    $group = $this->getGroup('maxsize');
    $acl = 'disable'; /* disabled by default */
    if (isset(app()->pluginAcl[$this->name])) {
      $acl = app()->pluginAcl[$this->name]['access'];
    }
    if ($acl!='enable') {
      /* reset to default */
      app()->user->setInfo('max_upload_size',app()->config['max_upload_size']*1024*1024);
    } else {
      $this->loadConfig();

      if (!isset($this->config['maxsize'][$group]) and isset($this->config['maxsize']['*'])>0) {
        $this->config['maxsize'][$group]=$this->config['maxsize']['*'];
      }
      if ($this->config['maxsize'][$group]>0) {
        app()->user->setInfo('max_upload_size',$this->config['maxsize'][$group]*1024*1024);
      }
    }
    ini_set('max_upload_size',app()->user->info('max_upload_size'));
    ini_set('post_max_size',app()->user->info('max_upload_size'));
  }
}
?>