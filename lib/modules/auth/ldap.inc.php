<?php

class ldapAuth extends authBase {
var $config;

  function ldapAuth() {
  }

  function init() {
    $this->config = app()->config['ldap'];
    $this->ufield = isset($this->config['uid'])?$this->config['uid']:'uid';
    $this->gfield = isset($this->config['gid'])?$this->config['gid']:'gid';
    /* cannot add or edit users for now */
    $this->features = array('useradmin' => 'no', 'groupadmin' => 'no');
  }

  function connect() {
    $this->ds=@ldap_connect($this->config['host']);
    if ($this->ds) {
      @ldap_set_option($this->ds, LDAP_OPT_PROTOCOL_VERSION, 3);
      @ldap_set_option($this->ds, LDAP_OPT_REFERRALS, 0); 
      return true;
    } else {
      app()->error(tr('LDAP connection failed!'));
    }
    return false;
  }

  function disconnect() {
    @ldap_unbind($this->ds);
  }

  function bind() {
    if (@ldap_bind($this->ds, $this->config['user'],$this->config['password']) )
      return true;
    return false;
  }

  function authenticate($login,$password) {
    $result = false;
    /* just to be sure */
    $this->disconnect();
    if ($this->connect()) {
      if ($this->config['type'] != 'AD') {
        $uid = $this->ufield.'='.$login.','.$this->config['userdn'];
      } else {
        $uid = $login.'@'.$this->config['domain'];
      }
      if ($uid!=NULL and $password!=NULL) {
        /* prevent injection (?), and special chars, thanks to Jason Weir */
        if (@ldap_bind($this->ds, $uid, $password)===TRUE) {
          $result = true;
        }
      }
      $this->disconnect();
    }
    return $result;
  }

  function userinfo($login) {
    $result = array();
    if ($this->connect() and $this->bind()) {
      $r = @ldap_search($this->ds, $this->config['userdn'],
                       '(&('.$this->ufield.'='.$login.')(objectclass='.$this->config['userclass'].'))');
      if ($r) {
        $res = @ldap_get_entries($this->ds, $r);
        /* associate user fields */;
        $res = $res[0];
        foreach ($this->config['userfields'] as $n => $f) {
          if ($f == 'group_id') {
            $result[$f] = $res[$n];
          } else {
            $result[$f] = $res[$n][0];
          }
        }
      }
      if ($this->config['type']!='AD') {
        /* now retrieve the main group */
        for ($g = 0; $g < $result['group_id']['count']; $g++) {
          $r = @ldap_search($this->ds, $this->config['groupdn'], 
                 '(&('.$this->gfield.'='.$result['group_id'][$g].')(objectclass='.$this->config['groupclass'].'))');
          if ($r) {
            $res = @ldap_get_entries($this->ds, $r);
            /* associate user fields */
            $res = $res[0];
            foreach ($this->config['groupfields'] as $n => $f) {
              if ($f == 'name' and $res[$n][0]!='') {
                $result['group'][] = $res[$n][0];
              }
            }
          }
        }
      } else {
        $result['group'][0] = app()->config['register']['default_group'];
        $this->config['sgid'] = $this->config['gid'];
        $this->config['sgroupfields'] = $this->config['groupfields'];
      }

      if (isset($this->config['sgid'])) {
        if ($this->config['type']!='AD')
          $result['uid'] = $result['login'];
        $filter = '(&('.$this->config['sgid'].'='.$result['uid'].')(objectclass='.$this->config['groupclass'].'))';
        $r = @ldap_search($this->ds, $this->config['groupdn'], $filter);
        if ($r) {
          $res = @ldap_get_entries($this->ds, $r);
          for ($i = 0; $i<$res['count']; $i++) {
            foreach ($this->config['sgroupfields'] as $n => $f) {
              if ($f == 'name' and $res[$i][$n][0]!='') {
                $result['group'][] = $res[$i][$n][0];
              }
            }
          }
        }
      }
    }
    $this->disconnect();
    return $result;
  }

  function groupinfo($group = '') {
    $result = array();
    if ($this->connect()) {
      $this->bind();
      if (group != '') {
        $r = @ldap_search($this->ds, $this->config['groupdn'],'(objectclass='.$this->config['groupclass'].')');      
      } else {
        $r = @ldap_search($this->ds, $this->config['groupdn'],
              '(&('.$this->gfield.'='.$group.')(objectclass='.$this->config['groupclass'].'))');
      }
      if ($r) {
        $res = @ldap_get_entries($this->ds, $r);
        /* associate user fields */
        for ($i = 0; $i<$res['count']; $i++) {
          foreach ($this->config['groupfields'] as $n => $f) {
            $result[$i][$f] = $res[$i][$n][0];
          }
        }
      }
      $this->disconnect();
    }
    return $result;
  }

}

?>
