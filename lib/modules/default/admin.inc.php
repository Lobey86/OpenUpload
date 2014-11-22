<?php

class AdminModule extends OpenUploadModule {
var $actions = array (
      "admin" => array (
        1 => "admin",
      ),
      "adminsettings" => array (
        1 => "settings",
        2 => "options",
      ),
      "adminplugins" => array (
        1 => "plugins",
      ),
      "adminpluginsacl" => array (
        1 => "pluginsacl",
        2 => "pluginadd",
        3 => "pluginedit",
        4 => "plugindel",
        5 => "pluginmultidel",
      ),
      "adminpluginsoptions" => array (
        1 => "pluginoptions",
        2 => "pluginoptiondetail",
        3 => "pluginoptionadd",
        4 => "pluginoptionedit",
        5 => "pluginoptiondelete",
      ),
      "adminfiles" => array (
        1 => "files",
        2 => "filedel",
        3 => "filesplugin",
        4 => "filemultidel",
      ),
      "adminmaintenance" => array (
        1 => "maintenance",
        2 => "maintenancerun",
      ),
      "adminusers" => array (
        1 => "users",
        2 => "useradd",
        3 => "useredit",
        4 => "userdel",
        5 => "useractivate",
        6 => "usermultidel",
      ),
      "admingroups" => array (
        1 => "groups",
        2 => "groupadd",
        3 => "groupedit",
        4 => "groupdel",
        5 => "groupmultidel",
      ),
      "adminrights" => array (
        1 => "rights",
        2 => "rightedit",
      ),
      "adminbanned" => array (
        1 => "banned",
        2 => "bannedadd",
        3 => "bannededit",
        4 => "banneddel",
        5 => "bannedmultidel",
      ),
      "adminlangs" => array (
        1 => "langs",
        2 => "langadd",
        3 => "langedit",
        4 => "langdel",
        5 => "langtoggle",
        6 => "langmultidel",
      ),
      "adminlogs" => array (
        1 => "logs",
      ),
      "adminupgrade" => array (
        1 => "upgrade",
        2 => "upgraderesult"
      ),
    );

var $page;

  function AdminModule() {
    $this->menu = array (
      "admin" => tr("Administration"),
    );
    foreach ($this->actions as $a => $v) {
      $this->page[$a] = array ("title" => tr("Administration"));
    }
  }

  function init() {
    /* only if the user has admin privileges let it see the module */
   
  }

  function admin() {
    $v = app()->db->read('plugin_options',array('plugin' => 'upgrade', 'name' => 'version'));
    if (count($v)==0) {
      app()->tpl->assign('upgrade','true');
    } 
  }

  function users() {
    /* List the users */
    if (app()->auth->features['useradmin'] == 'yes') {
      $users = app()->auth->users();
      $this->tpl->assign('users',$users);
    } else {
      app()->error(tr('User administration not supported by Auth Module'));
    }
  }

  function useradd() {
    global $_POST;
    
    $groups = app()->auth->groupinfo();
    /* do the assoc */
    $this->tpl->assign('groups',$groups);
    if (isset($_POST['adduserlogin'])) {
      /* add the user */
      $user['login']=$_POST['adduserlogin'];
      $user['name']=$_POST['addusername'];
      $user['group_name']=$_POST['addusergroup'];
      $user['email']=$_POST['adduseremail'];
      $user['active']=$_POST['adduseractive'];
      $user['lang']=$_POST['adduserlang'];
      $error = false;
      if (strlen($_POST['adduserlogin'])<5) {
        app()->error(tr('Login name must be at least 5 char long!'));
        $error = true;
      }
      if (strlen($_POST['adduserpassword'])<5) {
        app()->error(tr('Password must be at least 5 char long!'));
        $error = true;
      } else if ($_POST['adduserpassword']!=$_POST['adduserrepassword']) {
        app()->error(tr('Passwords do not correspond!'));
        $error = true;
      }
      if (!validEmail($_POST['adduseremail'])) {
        app()->error(tr('e-mail is not a valid address!'));
        $error = true;
      }
      if (!$error)  {
        $user['password']=$_POST['adduserpassword'];
        app()->auth->useradd($user);
        /* redirect */
        $this->nextStep(1);
      }
    }
    if (!isset($user)) { 
      $user['active']=1;
      $user['group_name']=app()->config['register']['default_group'];
    }
    $this->tpl->assign('adduser',$user);
  }

  function userdel() {
    global $_GET;

    if (isset($_GET['id'])) {
        /* avoid deleting the user which is logged in ... */
      if ($_GET['id'] != app()->user->info('login'))
        app()->auth->userdel($_GET['id']);
      else
        app()->error(tr('Cannot delete yourself!'));
    }
    $this->nextStep(1);
  }

  function usermultidel() {
    global $_POST;

    foreach ($_POST as $k => $v) {
      if ((strpos($k,'user_'))===0) {
        /* avoid deleting the user which is logged in ... */
        if ($v != app()->user->info('login'))
          app()->auth->userdel($v);
      else
        app()->error(tr('Cannot delete yourself!'));
      }
    }
    $this->nextStep(1);
  }

  function useractivate() {
    global $_GET;

    if (isset($_GET['id'])) {
      $active=$_GET['active']==1?0:1;
      $user = app()->auth->userinfo($_GET['id']);
      if ($user['login']==$_GET['id']) {
        $user['active']=$active;
        app()->auth->useredit($user,false);
      }
    }
    $this->nextStep(1);
  }

  function useredit() {
    global $_GET;
    global $_POST;
    /* edit the user */
    $groups = app()->auth->groupinfo();
    $this->tpl->assign('groups',$groups);
    if (isset($_POST['login'])) {
      $pwd = false;
      $user = app()->auth->userinfo($_POST['login']);
      $user['name']=$_POST['editusername'];
      $user['group_name']=$_POST['editusergroup'];
      $user['email']=$_POST['edituseremail'];
      $user['lang']=$_POST['edituserlang'];
      $user['active']=$_POST['edituseractive'];
      $error = false;
      if ($_POST['edituserpassword']!='') {
        if (strlen($_POST['edituserpassword'])<5) {
          app()->error(tr('password must be at least 5 char long!'));
          $error = true;
        } else if ($_POST['edituserpassword']!=$_POST['edituserrepassword']) {
          app()->error(tr('Passwords do not correspond!'));
          $error = true;
        }
        if (!$error) {
          $user['password']=$_POST['edituserpassword'];
          $pwd = true;
        }
      }
      if (!validEmail($_POST['edituseremail'])) {
        app()->error(tr('e-mail is not a valid address!'));
        $error = true;
      }
      if (!$error)  {
        app()->auth->useredit($user,$pwd);
        /* redirect */
        $this->nextStep(1);
      }
    } else {
      $user = app()->auth->userinfo($_GET['id']);
    }
    $this->tpl->assign('edituser',$user);
  }

  function groups() {
    if (app()->auth->features['groupadmin']=='yes') {
      $groups = app()->auth->groupinfo();
      $this->tpl->assign('groups',$groups);
    } else {
      app()->error(tr('Group administration not supported by Auth Module'));
    }
  }

  function groupadd() {
    global $_POST;

    if (app()->auth->features['groupadmin']=='yes') {
      if (isset($_POST['addgroupname'])) {
        $group['name']=$_POST['addgroupname'];
        $group['description']=$_POST['addgroupdescription'];
        if ($group['name']!='') {
          app()->auth->groupadd($group);
          $this->nextStep(1);
        } else {
          app()->error(tr('Please provide a valid group name!'));
        }
      }
      app()->tpl->assign('group',$group);
    } else {
      app()->error(tr('Group administration not supported by Auth Module'));
    }
  }

  function groupedit() {
    global $_POST;
    global $_GET;

    if (app()->auth->features['groupadmin']=='yes') {
      $id = isset($_POST['editgroupname'])?$_POST['editgroupname']:$_GET['id'];
      $group = app()->auth->groupinfo($id);
      if (isset($_POST['editgroupname'])) {
        $group['name']=$_POST['editgroupname'];
        $group['description']=$_POST['editgroupdescription'];
        app()->auth->groupedit($group);
        $this->nextStep(1);
      }
      app()->tpl->assign('group',$group);
    } else {
      app()->error(tr('Group administration not supported by Auth Module'));
    }
  }

  function groupdel() {
    global $_GET;
    /* should check if sub users exsist */
    if (isset($_GET['id'])) {
      app()->auth->groupdel($_GET['id']);
      /* delete all the rights of the group */
      app()->db->delete('acl',array('group_name' => $_GET['id']));
      app()->db->delete('pluins_acl',array('group_name' => $_GET['id']));
    }
    $this->nextStep(1);
  }

  function groupmultidel() {
    global $_POST;

    foreach ($_POST as $k => $v) {
      if (($v == 1) and (strpos($k,'group_'))!==FALSE) {
        $group = substr($k,6,strlen($k)-6);
        app()->auth->groupdel($group);
        /* delete all the rights of the group */
        app()->db->delete('acl',array('group_name' => $group));
        app()->db->delete('plugin_acl',array('group_name' => $group));
      }
    }
    $this->nextStep(1);
  }

  function rights() {
    $groups = app()->auth->groupinfo();
    array_unshift($groups,array('name' => '*','description' => tr('Any group')));
    $this->tpl->assign('groups',$groups);
    $rights = app()->db->read('acl',array(),array('group_name','module'),'',array('group_name','module','action'));
    $this->tpl->assign('rights',$rights);
  }
 
  function checkAcl($acl,$group,$module,$action,&$comb) {
    $result = 'deny'; /* not defined are denyed by default */
    $comb['group']=$group;
    $comb['module']=$module;
    $comb['action']=$action;
    if (isset($acl[$group][$module][$action])) {
      $result = $acl[$group][$module][$action]['access'];
    } else if (isset($acl[$group][$module]['*'])) {
      $result = $acl[$group][$module]['*']['access'];
      $comb['action']='*';
    } else if (isset($acl[$group]['*']['*'])) {
      $result = $acl[$group]['*']['*']['access'];
      $comb['module']='*';
      $comb['action']='*';
    } else if (isset($acl['*'][$module][$action])) {
      $result = $acl['*'][$module][$action];
      $comb['group']='*';
    } else if (isset($acl['*'][$module]['*'])) {
      $result = $acl['*'][$module]['*']['access'];
      $comb['group']='*';
      $comb['action']='*';
    } else if (isset($acl['*']['*']['*'])) {
      $result = $acl['*']['*']['*']['access']; /* this should be avoided imho */
      $comb['group']='*';
      $comb['module']='*';
      $comb['action']='*';
    }
    return $result;
  }

  function rightedit() {
    global $_GET;
    global $_POST;


    if (isset($_POST['id'])) {
      $id = $_POST['id'];
      foreach ($_POST as $k => $v) {
        if (strpos($k,'right_')!==FALSE) {
          $key = explode('_',$k);
          /* delete and reinsert the right */
          app()->db->delete('acl',array('group_name' => $id, 'module' => $key[1], 'action' => $key[2]));
          if ($v != '') {
            $acl['group_name']=$id;
            $acl['module']=$key[1];
            $acl['action']=$key[2];
            $acl['access']=$v;
            app()->db->insert('acl',$acl);
          }
        }
      }
     $this->nextStep(2,'','id='.$id);
    } else {
      $id = $_GET['id'];
    }
    $this->tpl->assign('group',$id);
    $tmpmodules = app()->config['modules'];
    foreach ($tmpmodules as $k => $m) {
      if ($m != 'admin') {
        $modules[$m]['name'] = $m;
        $modules[$m]['actions'] = array_merge(app()->modules[$m]->actions,array('*' => array()));
      }
    }
    $modules['admin']['name'] = 'admin';
    $modules['admin']['actions'] = array('*' => array());
    $modules = array_merge($modules,array('*'=> array('name' => '['.tr('ANY').']','actions' => array('*'=> array()))));
    $this->tpl->assign('modules',$modules);
    $access['']='-';
    $access['allow']=tr('Allow');
    $access['deny']=tr('Deny');
    $this->tpl->assign('access',$access);

    $acl = array_merge(app()->db->read('acl',array('group_name' => $id),array('module','action'),'',
                                        array('group_name','module','action')),
                       app()->db->read('acl',array('group_name' => '*'),array('module','action'),'',
                                        array('group_name','module','action')));
    foreach ($modules as $mk => $m) {
      foreach ($m['actions'] as $a => $av) {
        $res = $this->checkAcl($acl,$id,$mk,$a,$comb);
        if ($res == 'allow') {
          $rightlist[$id][$mk][$a]['access']=$acl[$id][$mk][$a]['access'];
          $rightlist[$id][$mk][$a]['result']=tr('Allow');
          $rightlist[$id][$mk][$a]['comb']=$comb;
        } else {
          $rightlist[$id][$mk][$a]['access']=$acl[$id][$mk][$a]['access'];
          $rightlist[$id][$mk][$a]['result']=tr('Deny');
          $rightlist[$id][$mk][$a]['comb']=$comb;
        }
      }
    }
    $this->tpl->assign('rights',$rightlist);
    $this->tpl->assign('defaultgroup',app()->config['nologingroup']);
  }

  function files() {
    $NUM = 50;

    $page = 1;
    if (isset($_GET['page'])) {
      $page=$_GET['page'];
    }
    $limit = ($NUM*($page-1)).','.$NUM;
    $count = app()->db->count('files');
    $this->tpl->assign('pages',ceil($count / $NUM)+1);
    $this->tpl->assign('pagen',$page);
    $files = app()->db->read('files',array(),array('upload_date desc'),$limit);
    $this->tpl->assign('files',$files);
  }

  function filedelete($id) {
        app()->db->delete('files',array('id' => $id));
        app()->db->delete('file_options',array('file_id' => $id));
        unlink(app()->config['DATA_PATH'].'/'.$id);
  }

  function filedel() {
    global $_GET;

    if ($_GET['id']!='') {
      $f = app()->db->read('files',array('id'=>$_GET['id']));
      if ($f[0]['id']==$_GET['id']) {
        $this->filedelete($_GET['id']);
      }
    }
    $this->nextStep(1);
  }

  function filemultidel() {
    global $_POST;

    foreach ($_POST as $k => $v) {
      if (($v == 1) and (strpos($k,'file_'))!==FALSE) {
        $fid = substr($k,5,strlen($k)-5);
        $f = app()->db->read('files',array('id'=>$fid));
        if ($f[0]['id']==$fid) {
          app()->db->delete('files',array('id' => $fid));
          app()->db->delete('file_options',array('file_id' => $fid));
          unlink(app()->config['DATA_PATH'].'/'.$fid);
        }
      }
    }
    $this->nextStep(1);
  }

  function filesplugin() {
    global $_GET;
    if (isset($_GET['plugin'])) {
      if (isset(app()->plugins[$_GET['plugin']])) {
        app()->plugins[$_GET['plugin']]->fileaction();
      }
    }
    $this->nextStep(1);
  }
 
  function maintenance() {
    global $_SESSION;

    /* check if expired plugin is loaded */
    if (isset(app()->plugins['expire'])) {
      $this->tpl->assign('expireplugin','yes');
    }
    if (app()->auth->features['useradmin']!='no') {
      $users = app()->auth->users();
      $this->tpl->assign('users',$users);
    }
    $this->tpl->assign('criteria',$_SESSION['user']['del']['criteria']);
  }

  function maintenancerun() {
    global $_POST;
    global $_SESSION;

    if (isset($_POST['expire'])) {
      /* get all the files which have an expire date */
      $files = app()->db->readex('file_options',array(array(array('name','=','expire'), 
                                                            array('value','<=',date('Y-m-d',time()-(24 * 60 * 60))),
                                                            array('value','!=',''))));
      $result = array();
      foreach ($files as $f) {
        $result[]['id']=$f['file_id'];
      }
      $this->tpl->assign('files',$result);
      $_SESSION['user']['del']['files']=$result;
    } else if (isset($_POST['run'])) {
      $criteria = array();
      foreach ($_POST as $k => $p) {
        $_SESSION['user']['del']['criteria']=$_POST;
        if (strpos($k,'c_')!==FALSE) {
          $n = substr($k,2,strlen($k)-2);
          if ($_POST[$n]!='') {
            switch ($n) {
              case 'login': $criteria[] = array ('user_login','=',$_POST[$n]); 
                break;
              case 'older': $criteria[] = array('upload_date','<', date('Y-m-d',time()-($_POST[$n] * 24 * 60 * 60)));
                break;
              case 'date': $criteria[] = array('upload_date','>=', date('Y-m-d',(strtotime($_POST[$n]))));
                           $criteria[] = array('upload_date','<', date('Y-m-d',(strtotime($_POST[$n])+24*60*60)));
                break;
              case 'size': $criteria[] = array('size','>', $_POST[$n]*1024*1024);
                break;
            }
          } else {
            app()->error(tr('Specified criteria is not valid!'));
            $this->nextStep();
          }
        }
      }
      if (count($criteria)>0) {
        $files = app()->db->readex('files',array($criteria));
        $this->tpl->assign('files',$files);
        $_SESSION['user']['del']['files']=$files;
      } else {
        app()->error(tr('Please specify at least one criteria!'));
        $this->nextStep(1);
      }
    } else if (isset($_POST['delete'])) {
      if (count($_SESSION['user']['del']['files'])<=0) {
        unset($_SESSION['user']['del']['files']);
        $this->nextStep(1);
      }
      foreach ($_SESSION['user']['del']['files'] as $f) {
        $this->filedelete($f['id']);
      }
      $this->tpl->assign('deleted','true');
      $this->tpl->assign('files',$_SESSION['user']['del']['files']);
      unset($_SESSION['user']['del']);
    }
  }

  function plugins() {
  }

  function pluginsacl() {
    $plugins = app()->db->read('plugin_acl',array(),array('plugin'));
    $this->tpl->assign('plugins_acl',$plugins);
  }

  function pluginadd() {
    global $_POST;

    $plugins = app()->config['plugins'];
    $this->tpl->assign('pluginslist',$plugins);
    $groups = app()->auth->groupinfo();
    $this->tpl->assign('groups',$groups);
    $access['enable']=tr('Enable');
    $access['disable']=tr('Disable');
    $this->tpl->assign('access',$access);
    $plugin['access']='disable';
    if (isset($_POST['addplugingroup'])) {
      $plugin['group_name']=$_POST['addplugingroup'];
      $plugin['plugin']=$_POST['addpluginplugin'];
      $plugin['access']=$_POST['addpluginaccess'];
      app()->db->insert('plugin_acl',$plugin);
      $this->nextStep(1);
    }
    app()->tpl->assign('plugin',$plugin);
  }

  function pluginedit() {
    global $_POST;
    global $_GET;

    $plugin = app()->db->read('plugin_acl',array('id' => $_GET['id']));
    $plugin = $plugin[0];
    $plugins = app()->config['plugins'];
    $this->tpl->assign('pluginslist',$plugins);
    $groups = app()->auth->groupinfo();
    $this->tpl->assign('groups',$groups);
    $access['enable']=tr('Enable');
    $access['disable']=tr('Disable');
    $this->tpl->assign('access',$access);
    if (isset($_POST['editpluginid'])) {
      $plugin = app()->db->read('plugin_acl',array('id' => $_POST['editpluginid']));
      $plugin = $plugin[0];
      $plugin['group_name']=$_POST['editplugingroup'];
      $plugin['plugin']=$_POST['editpluginplugin'];
      $plugin['access']=$_POST['editpluginaccess'];
      app()->db->update('plugin_acl',$plugin,array('id' => $_POST['editpluginid']));
      $this->nextStep(1);
    }
    app()->tpl->assign('plugin',$plugin);
  }

  function plugindel() {
    global $_GET;
    /* should check if sub users exsist */
    if (isset($_GET['id'])) {
      app()->db->delete('plugin_acl',array('id' => $_GET['id']));
    }
    $this->nextStep(1);
  }

  function pluginmultidel() {
    global $_POST;

    foreach ($_POST as $k => $v) {
      if (($v == 1) and (strpos($k,'p_'))!==FALSE) {
        $p = substr($k,2,strlen($k)-2);
        app()->db->delete('plugin_acl',array('id' => $p));
      }
    }
    $this->nextStep(1);
  }

  function pluginoptions() {
    /* list the plugins */
    foreach (app()->config['plugins'] as $p) {
      $list[$p]['name']=$p;
      $list[$p]['description']=app()->plugins[$p]->description;
    }
    $this->tpl->assign('pluginlist',$list);
  }

  function pluginoptiondetail() {
    global $_GET;

    if (isset($_GET['id'])) {
      $groups = app()->auth->groupinfo();
      $options = app()->db->read('plugin_options',array('plugin' => $_GET['id']),array('group_name'),
                                  '',array('group_name','name'));
      $x = '['.tr('Any').']';
      $groups[]= array ('name' => '*', 'description' => $x);
      $this->tpl->assign('groups',$groups);
      $this->tpl->assign('plugin_options',$options);
      $this->tpl->assign('options',app()->plugins[$_GET['id']]->options);
      $this->tpl->assign('pluginname',$_GET['id']);
    } else {
      $this->nextStep(1);
    }
  }


  function pluginoptionadd() {
    global $_GET;
    global $_POST;

    if (isset($_GET['id']) or isset($_POST['id'])) {
      $plugin = isset($_POST['id'])?$_POST['id']:$_GET['id'];
      $poptions = app()->plugins[$plugin]->options;
      $groups = app()->auth->groupinfo();
      $x = '['.tr('Any').']';
      $groups[]= array ('name' => '*', 'description' => $x);
      $this->tpl->assign('groups',$groups);
      $this->tpl->assign('options',$poptions);
      $this->tpl->assign('pluginname',$plugin);
      if (isset($_POST['id'])) {
        $group = $_POST['gid'];
        /* should check if values for this group already exsist */
        $tmp = app()->db->read('plugin_options',array('group_name' => $group, 'plugin' => $plugin));
        if (count($tmp)>0) {
          app()->error(tr('Options for this group already exsist, please use the edit function!'));
        } else {
          foreach ($poptions as $o) {
            $val = array();
            $val['plugin']=$plugin;
            $val['group_name']=$group;
            $val['name']=$o['name'];
            $val['value'] = $_POST[$o['name']];
            app()->db->insert('plugin_options',$val);
          }
          $this->nextStep(2,'','id='.$plugin);
        }
      } else {
        $options = array();
      }
      $this->tpl->assign('plugin_options',$options);
    } else {
      $this->nextStep(1);
    }
  }

  function pluginoptionedit() {
    global $_GET;
    global $_POST;

    if (isset($_GET['id']) or isset($_POST['id'])) {
      $plugin = isset($_POST['id'])?$_POST['id']:$_GET['id'];
      $group = isset($_POST['gid'])?$_POST['gid']:$_GET['gid'];
      $options = app()->db->read('plugin_options',array('plugin' => $plugin, 'group_name' => $group),array(),'',
                                 array('name'));
      $poptions = app()->plugins[$plugin]->options;
      if (isset($_POST['id'])) {
        foreach ($poptions as $o) {
          $val = array();
          if (isset($options[$o['name']])) {
            $val = $options[$o['name']];
            $val['value'] = $_POST[$o['name']];
            app()->db->update('plugin_options',$val,array('id' => $val['id']));
          } else {
            $val['plugin']=$plugin;
            $val['group_name']=$group;
            $val['name']=$o['name'];
            $val['value'] = $_POST[$o['name']];
            app()->db->insert('plugin_options',$val);
          }
        }
        $this->nextStep(2,'','id='.$plugin);
      } else if ($group!='') {
        $this->tpl->assign('plugin_options',$options);
        $this->tpl->assign('options',$poptions);
      } else {
        $this->nextStep(2,'','id='.$plugin);
      }
      $this->tpl->assign('gid',$group);
      $this->tpl->assign('pluginname',$plugin);
    } else {
      $this->nextStep(1);
    }
  }

  function pluginoptiondelete() {
    global $_GET;
 
    if (isset($_GET['id'])) {
      app()->db->delete('plugin_options',array('group_name' => $_GET['gid'], 'plugin' => $_GET['id']));
      $this->nextStep(2,'','id='.$_GET['id']);
    }
  }

  function settings() {
    $this->tpl->assign('config',app()->config);
  }

  function database() {
  }

  function listModules($path,$ext = 'inc.php') {
    /* now list the available database types */
    $dir = opendir($path);
    $result = array();
    while ($d = readdir($dir)) {
      if ($ext != '') {
        $n = explode('.',$d,2);
        if ($n[1]==$ext)
          $result[] = $n[0];
      } else {
       $result[] = $d;
      }
    }
    closedir($dir);
    return $result;
  }

  function generateConfig($CONFIG) {

    $result = '<?php'."\n";
    foreach ($CONFIG as $k => $v) {
      if (is_array($v)) {
        foreach ($v as $sk => $sv) {
          $result .= '$CONFIG[\''.$k.'\'][\''.$sk.'\'] = \''.str_replace('\'','\\\'',$sv).'\';'."\n";
        }
        $result .= "\n\n";
      } else {
        $result .= '$CONFIG[\''.$k.'\'] = \''.str_replace('\'','\\\'',$v).'\';'."\n\n";
      }
    }
    $result .='?>';
    return $result;
  }

  function options() {
    $loglevels = array ( 'Disabled', 'Errors', 'Security', 'Warnings', 'Statistics', 'Info');
    $tr = $this->listModules(app()->config['INSTALL_ROOT'].'/lib/modules/tr');
    $auth = $this->listModules(app()->config['INSTALL_ROOT'].'/lib/modules/auth');
    $tmp = $this->listModules(app()->config['INSTALL_ROOT'].'/templates','');
    foreach ($tmp as $t) {
      if ($t != '..' and $t != '.' and strpos($t,'.')!==0) {
         $templates[]=$t;
      }
    }
    $progress_values = array('none');
    if (function_exists('uploadprogress_get_info')) {
      $progress_values[]='uploadprogress';
    }
    if (function_exists('apc_fetch')) {
      if (ini_get('apc.enabled')) {
        if (ini_get('apc.rfc1867')) {
          $progress_values[]='apc';
        }
      }
    }
    $config = array();
    if (count($_POST)>0) {
      $config['translator']=$_POST['translator'];
      $config['defaultlang']=$_POST['defaultlang'];
      $config['auth']=$_POST['auth'];
      $config['site']['title']=$_POST['sitetitle'];
      $config['site']['footer']=str_replace('\"','"',$_POST['sitefooter']);
      $config['site']['webmaster']=$_POST['webmaster'];
      $config['site']['email']=$_POST['email'];
      $config['registration']['email_confirm']=isset($_POST['confirmregistration'])?'yes':'no';
      $config['site']['template']=$_POST['template'];
      $config['multiupload']=$_POST['multiupload'];
      $config['max_upload_size']=$_POST['max_upload_size'];
      $config['use_short_links']=isset($_POST['use_short_links'])?'yes':'no';
      $config['id_max_length']=$_POST['id_max_length'];
      $config['id_use_alpha']=isset($_POST['id_use_alpha'])?'yes':'no';;
      $config['allow_unprotected_removal']=isset($_POST['allow_unprotected_removal'])?'yes':'no';;
      $config['progress']=$_POST['progress'];
      $config['logging']['enabled']=isset($_POST['logging'])?'yes':'no';
      $config['logging']['db_level']=$_POST['log_db_level'];
      $config['logging']['syslog_level']=$_POST['log_syslog_level'];
    }
    if (isset($_POST['save'])) {
      /* save the configuration file */
      $config = array_merge(app()->config,$config);
      //unset($config['plugins']);
      unset($config['modules']);
      $cfgfile = $this->generateConfig($config);
      $file = 'config.inc.php';
      if (defined('__NOT_MAIN_SCRIPT')) {
        $file = 'www/'.$file;
      }
      if (@file_put_contents($file,$cfgfile)) {
        app()->message(tr('Configuration sucessfully saved!'));
      } else {
        app()->error(tr('Configuration file could not be saved, please proceed with the download!'));
      }
    } else if (isset($_POST['download'])) {
      /* send the configuration file */
      $config = array_merge(app()->config,$config);
      //unset($config['plugins']);
      unset($config['modules']);
      $cfgfile = $this->generateConfig($config);
      ob_clean();
      header('Content-Type: text/plain');
      header('Content-Length: '.strlen($result));
      header('Content-Disposition: attachment; filename="config.inc.php"');
      echo $cfgfile;
      exit;
    } else {
      $config = array_merge(app()->config,$config);
    }
    $this->tpl->assign('config',$config);

    $this->tpl->assign('auth',$auth);
    $this->tpl->assign('tr',$tr);
    $this->tpl->assign('progress',$progress_values);
    $this->tpl->assign('templates',$templates);
    $this->tpl->assign('loglevels',$loglevels);
  }

  function banned() {
    $NUM = 50;

    $page = 1;
    if (isset($_GET['page'])) {
      $page=$_GET['page'];
    }
    $limit = ($NUM*($page-1)).','.$NUM;
    $count = app()->db->count('banned');
    $this->tpl->assign('pages',ceil($count / $NUM)+1);
    $this->tpl->assign('pagen',$page);
    $banned = app()->db->read('banned',array(),array('priority'),$limit);
    $this->tpl->assign('banned',$banned);
  }

  function bannedadd() {
    global $_GET;
    global $_POST;

    if (isset($_GET['ip'])) {
      $ip = $_GET['ip'];
      $ban = app()->db->read('banned',array('ip' => $ip));
      $ban = $ban[0];
      if ($ban['ip']!=$ip) {
        $ban['priority']='10'; /* maybe a bigger one is better */
        $ban['ip']=$ip;
        $ban['access']='deny';
        app()->db->insert('banned',$ban);
        app()->message(tr('IP %1 has been banned!',$ip));
        if (isset($_GET['nextaction']))
         $this->nextStep(1,$_GET['nextaction']);
      } else {
        app()->error(tr('IP %1 was already in state: %2!',$ip,$ban['access']));
        if (isset($_GET['newaction']))
          $this->nextStep(1,$_GET['newaction']);
      }
    } else if (isset($_POST['addbannedip'])) {
      $ip = $_POST['addbannedip'];
      $ban = app()->db->read('banned',array('ip' => $ip));
      $ban = $ban[0];
      if ($ban['ip']!=$ip) {
        $ban['priority']=$_POST['addbannedpriority']; 
        $ban['ip']=$ip;
        $ban['access']=$_POST['addbannedaccess'];
        app()->db->insert('banned',$ban);
        $this->nextStep(1);
      } else {
        app()->error(tr('IP %1 was already in state: %2!',$ip,$ban['access']));
      }
    } else {
      $ban['access']='deny';
    }
    $this->tpl->assign('access',array('allow' => tr('Allow'), 'deny' => tr('Deny')));
    $this->tpl->assign('banned',$ban);
  }

  function bannededit() {
    global $_GET;
    global $_POST;

    if (isset($_GET['id'])) {
      $id = $_GET['id'];
      $ban = app()->db->read('banned',array('id' => $id));
      $ban = $ban[0];
      if ($ban['id']!=$id) {
        $this->nextStep(1);
      }
    } else if (isset($_POST['editbannedid'])) {
      $id = $_POST['editbannedid'];
      $ban = app()->db->read('banned',array('id' => $id));
      $ban = $ban[0];
      if ($ban['id']==$id) {
        $ban['priority']=$_POST['editbannedpriority']; 
        $ban['ip']=$_POST['editbannedip'];
        $ban['access']=$_POST['editbannedaccess'];
        app()->db->update('banned',$ban,array('id' => $ban['id']));
      }
      $this->nextStep(1);
    }
    $this->tpl->assign('access',array('allow' => tr('Allow'), 'deny' => tr('Deny')));
    $this->tpl->assign('banned',$ban);
  }

  function banneddel() {
    app()->db->delete('banned',array('id' => $_GET['id']));
    $this->nextStep(1);
  }

  function bannedmultidel() {
    global $_POST;

    foreach ($_POST as $k => $v) {
      if (($v == 1) and (strpos($k,'ban_'))!==FALSE) {
        $ban = substr($k,4,strlen($k)-4);
        /* delete all the rights of the group */
        app()->db->delete('banned',array('id' => $ban));
      }
    }
    $this->nextStep(1);
  }

  function bannedup() {
  }
  function banneddown() {
  }

  function langs() {
    $langs = app()->db->read('langs');
    $this->tpl->assign('langlist',$langs);
  }

  function langadd() {
    if (isset($_POST['addlangid'])) {
      $lang['id']=$_POST['addlangid'];
      $lang['name']=$_POST['addlangname'];
      $lang['locale']=$_POST['addlanglocale'];
      $lang['browser']=$_POST['addlangbrowser'];
      $lang['charset']=$_POST['addlangcharset'];
      $lang['active']=isset($_POST['addlangactive'])?1:0;
      //$this->tpl->assign('lang' , $lang[0]);
      $tmp = app()->db->read('langs',array('id' => $_POST['id']));
      if ($lang['id']=='') {
        $error = true;
        app()->error(tr('Language "%1" cannot be empty',tr('ID')));
      } else if ($tmp[0]['id']==$lang['id']) {
        $error = true;
        app()->error(tr('Language "%1" already exists!',$lang['id']));
      }
      if ($lang['name']=='') {
        $error = true;
        app()->error(tr('Language "%1" cannot be empty!',tr('Name')));
      }
      if ($lang['locale']=='') {
        $error = true;
        app()->error(tr('Language "%1" cannot be empty!',tr('Locale')));
      }
      if ($lang['charset']=='') {
        $error = true;
        app()->error(tr('Language "%1" cannot be empty!',tr('Charset')));
      }
      if (!$error) {
        app()->db->insert('langs',$lang);
        $this->nextStep(1);
      }
    } else {
      $lang['name']='New language';
      $lang['active']=1;
    } 
        
  }

  function langedit() {
    global $_GET;

    if (isset($_POST['id'])) {
      $lang = app()->db->read('langs',array('id' => $_POST['id']));
      $lang = $lang[0];
      $lang['name']=$_POST['editlangname'];
      $lang['locale']=$_POST['editlanglocale'];
      $lang['browser']=$_POST['editlangbrowser'];
      $lang['charset']=$_POST['editlangcharset'];
      $lang['active']=isset($_POST['editlangactive'])?1:0;
      //$this->tpl->assign('lang' , $lang[0]);
      app()->db->update('langs',$lang,array('id' => $_POST['id']));
      $this->nextStep(1);
    } else if (isset($_GET['id'])) {
      $lang = app()->db->read('langs',array('id' => $_GET['id']));
      $this->tpl->assign('lang', $lang[0]);
    } else {
      $this->nextStep(1);
    }
  }
 
  function langdel() {
    global $_GET;

    if (isset($_GET['id'])) {
      app()->db->delete('langs',array( 'id' => $_GET['id']));
    }
    $this->nextStep(1);
  }

  function langmultidel() {
    global $_POST;

    foreach ($_POST as $k => $v) {
      if (($v == 1) and (strpos($k,'lang_'))!==FALSE) {
        $lang = substr($k,5,strlen($k)-5);
        app()->db->delete('langs',array('id' => $lang));
      }
    }
    $this->nextStep(1);
  }

  function langtoggle() {
    global $_GET;

    if (isset($_GET['id'])) {
      $active=$_GET['active']==1?0:1;
      $lang = app()->db->read('langs',array('id' => $_GET['id']));
      $lang = $lang[0];
      if ($lang['id']==$_GET['id']) {
        $lang['active']=$active;
        app()->db->update('langs',$lang,array('id' => $_GET['id']),array('active'));
      }
    }
    $this->nextStep(1);
  }

  function logs() {
    global $_GET;

    $NUM = 50;

    $page = 1;
    if (isset($_GET['page'])) {
      $page=$_GET['page'];
    }
    $filter = array();
    if (isset($_GET['level'])) {
      if ($_GET['level']!='') {
        $filter = array('level' => $_GET['level']);
      }
    }
    $limit = ($NUM*($page-1)).','.$NUM;
    $count = app()->db->count('activitylog',$filter);
    $this->tpl->assign('pages',ceil($count / $NUM)+1);
    $this->tpl->assign('pagen',$page);
    $this->tpl->assign('level',$_GET['level']);
    $logs = app()->db->read('activitylog',$filter,array('log_time desc'),$limit);
    $this->tpl->assign('logs',$logs);

  }

  function upgrade() {
    $v = app()->db->read('plugin_options',array('plugin' => 'upgrade', 'name' => 'version'));
    if (count($v)>0) {
      app()->tpl->assign('version',$v);
    }    
  }

  function upgraderesult() {
    global $_GET;
  
     /* run the upgrade */
    if (isset($_GET['upgrade'])) {
      $errors = array();
      $fields = array('ip','user_login','module','action','realaction','plugin','result','moreinfo');
      $logs = app()->db->read('activitylog');        
      foreach ($logs as $l) {
        foreach ($fields as $f) {
          $l[$f]=htmlentities($l[$f]);
        }
        if (!app()->db->update('activitylog',$l,array('id' => $l['id'])))
          $errors[] = 'ERROR updating activitylog ID: '.$l['id'];
      }
      /* now update the files */
      $files = app()->db->read('files');
      foreach ($files as $f) {
        $f['description']=htmlentities($f['description']);
        if (!app()->db->update('files',$f,array('id' => $f['id'])))
            $errors[] = 'ERROR updating files ID: '.$f['id'];
      }
      /* now the user names */
      $users = app()->db->read('users');
      foreach ($users as $u) {
        $u['name']=htmlentities($u['name']);
        if (!app()->db->update('users',$u,array('id' => $u['id'])))
           $errors[] = 'ERROR updating user ID: '.$u['login'];
      }
      app()->tpl->assign('upgradeerrors',$errors);
      if (count($errors)==0) {
        $x = array('plugin' => 'upgrade', 'group_name' => '*', 'name' => 'version', 'value' => '0.4.2');
        app()->db->insert('plugin_options',$x);
      }
    } else {
      app()->error('ERROR: No upgrade was RUN');
      $this->nextStep(1);
    }
  }
}
?>