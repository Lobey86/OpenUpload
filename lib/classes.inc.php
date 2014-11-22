<?php

class translatorBase {
  function translatorBase() {}

  function init() {}

  function translate($txt) {
    return $txt;
  }
}

class authBase {
var $features = array('adminuser' => 'no', 'admingroup' => 'no');

  function authBase() {
  }
  
  function init() {}

  function authenticate($user,$pwd) { return false; }

  function userinfo($login) { return array(); }
  function groupinfo($group = '') { return array(); }

  function users() { return array(); }
  function useradd($user) {}
  function useredit($user) {}
  function userdel($id) {}
  function groupadd($group) {}
  function groupedit($group) {}
  function groupdel($id) {}
   
}

class dbBase {

  function dbBase($config = array()) {
    
  }
  
  function init() {

  }
  
  function free() {

  }

  function newId($tbl,$field = 'id',$keys = array ()) {
    app()->error('Please reimplement: '.$this->name.' newId');
    return 0;
  }

  function newRandomId($tbl,$field = 'id') {
    app()->error('Please reimplement: '.$this->name.' newRandomId');
    $id = randomName(30,30);
    return $id;
  }

  function count($tbl,$keys = array()) {
    app()->error('Please reimplement: '.$this->name.' count');
    return 0;
  }

  function read($tbl,$keys = array(), $sort = array(), $limit = '', $assoc = array()) {
    app()->error('Please reimplement: '.$this->name.' read');
    return array();
  }

  function insert($tbl,$values,$fields = array()) {
    app()->error('Please reimplement: '.$this->name.' insert');
    return false;
  }

  function update($tbl,$values,$keys = array(),$fields = array()) {
    app()->error('Please reimplement: '.$this->name.' update');
    return false;
  }

  function delete($tbl,$keys = array()) {
    app()->error('Please reimplement: '.$this->name.' delete');
    return false;
  }

}

class OpenUploadModule {
var $actions = array();
var $access = array();
var $page = array();
var $tpl;
  
  function OpenUploadMoule() {
  }

  function nextStep($step = 0, $action = '',$params='') {
    $step = $step==0?app()->step+1:$step;
    $action = $action==''?app()->action:$action;
    if ($params!='')
      redirect('?action='.$action.'&step='.$step.'&'.$params);
    else
      redirect('?action='.$action.'&step='.$step);
  }

  function prevStep() {
    $step = app()->step>1?app()->step-1:1;
    $action = app()->action;
    redirect('?action='.$action.'&step='.$step);
  }

  function fileaction() {
  }

  function init() {
  }
  
}

class OpenUploadPlugin {
  var $pluginHTML = '';
  var $messageHTML;
  var $name;
  var $fields = array();
  var $options = array();
  var $config = array();

  function OpenUploadPlugin() {
  }

  function assign($name, $value) {
    app()->tpl->assign($name,$value);
  }

  function display($tpl) {
    $this->pluginHTML .= app()->fetch('plugins/'.$this->name.'/'.$tpl);
  }

  function loadConfig() {
    if (count($this->options)>0) {
      $opt = app()->db->read('plugin_options',array('plugin' => $this->name),array(),'',array('name','group_name'));
      if (count($opt)==0) return;
      foreach ($this->options as $o) {
        $this->config[$o['name']] = array();
        if (isset($opt[$o['name']])) {
          foreach ($opt[$o['name']] as $g => $v) {
            switch ($o['type']) {
              case 'list':
                $this->config[$o['name']][$g] = explode("\n",chop($v['value']));
                foreach ($this->config[$o['name']][$g] as $k => $z) {
                  $this->config[$o['name']][$g][$k] = chop($z);
                }
                break;
              case 'text':
                $this->config[$o['name']][$g] = $v['value'];
                break;
              default:
                $this->config[$o['name']][$g] = $v['value'];
                break;
            }
          }
        }
      }
    }
  }

  function getGroup($option) {
    $group = app()->user->group();
    if (is_array($group)) {
      /* check for which group there is a configuration */
      foreach ($group as $g) {
        if (isset($this->config[$option][$g])) {
          if (count($this->config[$option])) {
            return $g;
          }
        } 
      }
      return $group[0];
    } else {
      return $group;
    }
  }

  function init() {
    $this->loadConfig();
    
  }

/* functions that can be called 
 * all functions receive an array with the parameters      
 * some of them are called with an empty array = no params 
 * auth module  = loginForm, authenticate, logout,
 *                registerForm, registerConfirm, registerEnable
 * files module = uploadRequest, uploadOptions, uploadConfirm, UploadFileInfo,
 *                downloadForm, downloadRequest, downloadConfirm, serveFile,
 *                removeRequest, removeResult
 */

}

?>