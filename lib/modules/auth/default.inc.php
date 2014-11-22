<?php
/* use the db to Authenticate users */
class defaultAuth extends authBase {
var $db;
var $userfields;

  function defaultAuth() {
    $this->userfields = array('login','password','name','group_name','email','lang','reg_date','regid','active');
    $this->features = array('useradmin' => 'yes', 'groupadmin' => 'yes');
  }
  
  function init() {
    $this->db = app()->db;
  }
  
  function authenticate($login,$password) {
    $res = $this->db->read('users',array('login' => $login, 'active' => 1));
    $user = $res[0];
    if ($user['login']==$login and crypt($password,$user['password'])==$user['password']) {
      return true;
    } 
    return false;
  }

  function userinfo($login) {
    $result = $this->db->read('users',array('login' => $login));
    $result[0]['group']=$result[0]['group_name'];
    return $result[0];
  }

  function groupinfo($group = '') {
    if ($group != '') {
      $result = $this->db->read('groups',array('name' => $group));
      return $result[0];
    } else {
      $result = $this->db->read('groups',array(),array('name'));
      return $result;
    }
  }
 
  function users() {
    return app()->db->read('users',array(),array('login'));
  }

  function useradd($user) {
    $user['password']=crypt($user['password']);
    $this->db->insert('users',$user,$this->userfields);
  }

  function useredit(&$user,$pwd = false) {
    if ($pwd) {
      $user['password']=crypt($user['password']);
    }
    $this->db->update('users',$user,array('id' => $user['id']),$this->userfields);
  }

  function userdel($id) {
    $this->db->delete('users',array('login' => $id));
  }

  function groupadd($group) {
    $this->db->insert('groups',$group);
  }

  function groupedit($group) {
    app()->db->update('groups',$group,array('name' => $group['name']));
  }

  function groupdel($group_id) {
    app()->db->delete('groups',array('name' => $group_id));
  }
}

?>