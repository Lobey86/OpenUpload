<?php

class Application {
  var $db; /* database */
  var $auth; /* authentication */
  var $tr; /* trasnlation */
  var $config; /* condifuration */
  var $user; /* user */
  var $modules; /* modules */
  var $actions; /* actions related to modules */
  var $plugins; /* plugins for modules */
  var $page; /* page global config */
  var $acl; /* module acl */
  var $pluginAcl; /* plugin acl */

  function Application($CONFIG) {
    global $application;
    global $_POST;
    global $_GET;
    
    $application = $this;
    $this->config = $CONFIG;
    
    /* initialize template engine */
    $this->tpl = new Smarty();
    $this->tpl->template_dir = $this->config['INSTALL_ROOT'].'/templates';
    if (isset($this->config['SMARTY_DATA'])) {
      $this->tpl->compile_dir  = $this->config['SMARTY_DATA'].'/templates_c/';
      $this->tpl->cache_dir    = $this->config['SMARTY_DATA'].'/cache';
    } else {
      $this->tpl->compile_dir  = $this->config['INSTALL_ROOT'].'/templates_c/';
      $this->tpl->cache_dir    = $this->config['INSTALL_ROOT'].'/cache';
    }
    $this->tpl->config_dir   = SMARTY_DIR.'/configs';
    $this->tpl->caching      = $this->config['site']['caching'];

    $this->page['template']= $this->config['WWW_ROOT'].'/templates/'.$this->config['site']['template'];

    /* include the class first */
    $dbtype = $this->config['database']['type'];
    require_once($this->config['INSTALL_ROOT'].'/lib/modules/db/'.$dbtype.'.inc.php');
    $dbmname = $dbtype.'DB';
    $this->db = new $dbmname($this->config['database']);
    $this->db->init(); /* open db connection */

    /* authentication module */
    if (isset($this->config['auth'])) {
      $authmname = $this->config['auth'];
    } else {
      $authmname = 'default';
    }
    require_once($this->config['INSTALL_ROOT'].'/lib/modules/auth/'.$authmname.'.inc.php');
    $auth = $authmname.'Auth';
    $this->auth = new $auth();
    
    $this->user = new OpenUploadUser();
    $this->user->auth = &$this->auth;

    /* translation module */
    if (isset($this->config['translator'])) {
      $trname = $this->config['translator'];
    } else {
      $trname = 'null';
    }
    require_once($this->config['INSTALL_ROOT'].'/lib/modules/tr/'.$trname.'.inc.php');
    $tr = $trname.'Translator';
    $this->tr = new $tr();

    $this->langs = $this->db->read('langs',array('active' => '1'),array('id'),'',array('id'));

    /* check if it was forced */
    if (isset($_GET['lang'])) {
      $user = $this->user->info();
      $user['lang']=htmlentities($_GET['lang']);
      $this->user->setInfo('lang',htmlentities($_GET['lang']));
    }

    /* configure the language */
    if ($this->user->info('lang')=='') {
      $lang = $this->getBrowserLang();
      $user = $this->user->info();
      $this->user->setInfo('lang',$lang);
    }
    $this->tr->init();
    $this->auth->init();
    $this->user->init();
    if ($this->user->info('max_upload_size')==0) 
      $this->user->setInfo('max_upload_size',$this->config['max_upload_size']*1024*1024);
    ini_set('max_upload_size',$this->user->info('max_upload_size'));
    ini_set('post_max_size',$this->user->info('max_upload_size'));
// TODO: should check if this value has been really set or if it was blocked by the PHP

    $this->config['modules'][]='files';
    $this->config['modules'][]='admin';
    $this->config['modules'][]='auth';

    $this->loadACL();
    $this->initModules();

    $this->loglevels['error']    = array('id' => 1, 'syslog' => LOG_ERR     );
    $this->loglevels['security'] = array('id' => 2, 'syslog' => LOG_WARNING );
    $this->loglevels['warning']  = array('id' => 3, 'syslog' => LOG_WARNING );
    $this->loglevels['notice']   = array('id' => 4, 'syslog' => LOG_NOTICE  );
    $this->loglevels['info']     = array('id' => 5, 'syslog' => LOG_INFO    );
    $this->loglevels['debug']    = array('id' => 9, 'syslog' => LOG_DEBUG   );

    /* handle magic_quotes at the source */
    if (ini_get('magic_quotes_gpc')) {
       /* remove magic quoting from username and password */
      foreach ($_POST as $k => $v) {
        $_POST[$k] = stripslashes($v);
      }
      foreach ($_GET as $k => $v) {
        $_GET[$k] = stripslashes($v);
      }
    }
  }

  function getBrowserLang() {
    global $_SERVER;

    /* calculate preferred language */
    $langs = str_replace(' ','',strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']));
    $langs = explode(',',$langs);
    foreach ($langs as $ls) {
      $language = explode(';',$ls);
      foreach ($language as $l) {
        foreach ($this->langs as $ml) {
          if (strpos(strtolower($ml['browser']),'['.$l.']')!==FALSE) {
            return $ml['id'];
          }
          if (strpos($l,'-')) {
            $x = explode('-',$l);
            if (strpos(strtolower($ml['browser']),'['.$x[0].']')!==FALSE) {
              return $ml['id'];
            }
          }
        }
      }
    }
    return $this->config['defaultlang'];
  }

  function fetch($tname) {
    if (file_exists($this->tpl->template_dir.'/'.$this->config['site']['template'].'/'.$tname.'.tpl')) {
      return $this->tpl->fetch($this->config['site']['template'].'/'.$tname.'.tpl');
    } else {
      return $this->tpl->fetch('default/'.$tname.'.tpl');
    }
  }

  function display($tname) {
    if (file_exists($this->tpl->template_dir.'/'.$this->config['site']['template'].'/'.$tname.'.tpl')) {
      $this->tpl->display($this->config['site']['template'].'/'.$tname.'.tpl');
    } else {
      $this->tpl->display('default/'.$tname.'.tpl');
    }
  }

  function message($msg) {
    global $_SESSION;
    $_SESSION['user']['messages'][] = $msg;
    $this->log('info','','','MESSAGE',$msg);
  }

  function error($msg) {
    global $_SESSION;
    $_SESSION['user']['errors'][] = $msg;
    $this->log('info','','','ERROR',$msg);
  }

  function log($level,$realaction,$plugin,$result,$moreinfo) {
    global $_SERVER;

    // log: datetime, ip, user, module, action, realaction, plugin, resulting code, additional info
    if (isset($this->config['logging'])) {
      if ($this->config['logging']['enabled']=='yes') {
        $ip = $_SERVER['REMOTE_ADDR'];
        $login = htmlentities($this->user->info('login'));
        $module = $this->actions[htmlentities($this->action)];
        $action = htmlentities($this->action);
        $ip = htmlentities($ip);
        $realaction = htmlentities($realaction);
        $plugin = htmlentities($plugin);
        $result = htmlentities($result);
        $moreinfo = htmlentities($moreinfo);
        if ($this->config['logging']['db_level']>=$this->loglevels[$level]['id']) {
          if (is_object($this->db)) {
            $this->db->insert('activitylog', 
               array('level' => $level,
                     'log_time' => date('Y-m-d H:i:s'),
                     'ip' => $ip, 
                     'user_login' => $login,
                     'module' => $module,
                     'action' => $action,
                     'realaction' => $realaction,
                     'plugin' => $plugin,
                     'result' => $result,
                     'moreinfo' => $moreinfo
                    ) );
          }
        }
        if ($this->config['logging']['syslog_level']>=$this->loglevels[$level]['id']) {
          $msg = '[openupload] IP='.$ip.' LOGIN='.$this->user->info('login').
                 ' MODULE='.$module.' ACTION='.$action.' REALACTION='.$realaction.
                 ' PLUGIN='.$plugin.' RESULT='.$result.' MSG='.$moreinfo;
          syslog($this->loglevels[$level]['syslog'],$msg);
        }
      }
    }
  }

  function initModules() {
    /* initialize configured modules */
    foreach ($this->config['modules'] as $module) {
      /* create and initialize the module */
      require_once($this->config['INSTALL_ROOT'].'/lib/modules/default/'.$module.'.inc.php');
      $m = $module.'Module';
      $m = new $m();
      $m->name = $module;
      $m->tpl = &$this->tpl;
      foreach ($m->actions as $k => $l) {
        $this->actions[$k] = $m->name;
        $this->modules[$module] = $m;
      }
    }
    foreach ($this->modules as $m) {
      $m->init();
    }
  }

  function initMenu($auth = false) {
    
    $this->menu = array();
    foreach ($this->modules as $m) {
      foreach ($m->actions as $k => $a) {
        if (isset($m->menu[$k])) {
          $group = app()->user->group();
          if ($this->checkACL($group,$m->name,$k) == 'allow') {
            $this->menu[$k]=$m->menu[$k];
          }
        }
      }
    }
    $this->tpl->assign('menu',$this->menu); 
  }

  function initPlugins() {
    /* initialize plugin system */
    
    $this->plugins = array();
    /* load the plugins */
    foreach ($this->config['plugins'] as $plugin) {
      /* include the file */
      if (file_exists($this->config['INSTALL_ROOT'].'/plugins/'.$plugin.'.inc.php')) {
        require_once($this->config['INSTALL_ROOT'].'/plugins/'.$plugin.'.inc.php');
        $pname = $plugin."Plugin";
        $newp = new $pname();
        $newp->name = $plugin;
        $this->plugins[$plugin] = $newp;
      } else {
        $this->error(tr('plugin include file not found: %1',$plugin));
      }
    }
    foreach ($this->plugins as $plugin) {
      $plugin->init();
    }
  }

  function pluginAction($action,&$finfo,$stop = true) {
    $this->pluginHTML = '';
    $result = true;

    if (!is_array($this->plugins))
      return true;
    foreach ($this->plugins as $plugin) {
      if (method_exists($plugin,$action)) {
        /* check plugin acl */
        $acl = 'disable'; /* disabled by default */
        if (isset($this->pluginAcl[$plugin->name])) {
          $acl = $this->pluginAcl[$plugin->name]['access'];
        }
        if (!$plugin->$action($finfo,$acl)) {
          if ($stop) { 
            app()->log('security',$action,$plugin->name,'DENY','');
            return false;
          }
          app()->log('info',$action,$plugin->name,'DENY','non blocking');
          $result = false;
        }
        $this->pluginHTML .= $plugin->pluginHTML;
      }
    }
    return $result;
  }

  function loadACL() {
    /* loads the acl from the db */
    $group = $this->user->group();
    if (is_array($group)) {
      $this->acl = $this->db->read('acl',array(),array('group_name','module','action'),'',
                                   array('group_name','module','action'));
      $this->pluginAcl = $this->db->read('plugin_acl',array(),array('plugin'),'',array('plugin'));
    } else {
      $this->acl = array_merge($this->db->read('acl',array('group_name' => $group),array('module','action'),'',
                                                array('group_name','module','action')),
                               $this->db->read('acl',array('group_name' => '*'),array('module','action'),'',
                                                array('group_name','module','action')));
      $this->pluginAcl = $this->db->read('plugin_acl',array('group_name' => $group),array('plugin'),'',array('plugin'));
    }
  }


  function checkSingleACL($group,$module,$action) {
    
    $result = 'deny'; /* not defined are denyed by default */
    if (isset($this->acl[$group][$module][$action])) {
      $result = $this->acl[$group][$module][$action]['access'];
    } else if (isset($this->acl[$group][$module]['*'])) {
      $result = $this->acl[$group][$module]['*']['access'];
    } else if (isset($this->acl[$group]['*']['*'])) {
      $result = $this->acl[$group]['*']['*']['access'];
    } else if (isset($this->acl['*'][$module][$action])) {
      $result = $this->acl['*'][$module][$action];
    } else if (isset($this->acl['*'][$module]['*'])) {
      $result = $this->acl['*'][$module]['*']['access'];
    } else if (isset($this->acl['*']['*']['*'])) {
      $result = $this->acl['*']['*']['*']['access']; /* this should be avoided imho */
    }
    return $result;
  }

  function checkACL($group,$module,$action) {
    if (is_array($group)) {
      foreach ($group as $g) {
        $result = $this->checkSingleACL($g,$module,$action);
        if ($result == 'allow') {
          return $result;
        }
      }
    } else {
      $result = $this->checkSingleACL($group,$module,$action);
    }

    if (isset($this->config['debug_acl']) and $this->config['debug_acl'] and $result == 'deny') {
      echo '<pre>ACL: '.$result.' - group: '.$group.', module: '.$module.', action: '.$action."\n"; 
      print_r($this->acl);
      echo '</pre>';
      $result = 'allow';
    }

    return $result;
  }

  function convertSubnet($val) {
    $sub = array();
    if ($val<0) $val = 0;
    if ($val>32) $val = 32;
    for ($i=0; $i<4; $i++) {
      $x = 0; /* could be done with a for... */
      if ($val>0) $x += 128;
      if ($val>1) $x += 64;
      if ($val>2) $x += 32;
      if ($val>3) $x += 16;
      if ($val>4) $x += 8;
      if ($val>5) $x += 4;
      if ($val>6) $x += 2;
      if ($val>7) $x += 1;
      $sub[$i] = $x;
      $val = $val - 8;
    }
    return $sub;
  }

  function matchIP($sip,$exp) {
    if (strpos($exp,'/')!==FALSE) {
      $x = explode('/',$exp);
      $net = $x[0];
      $sub = $x[1];
      if (strpos($sub,'.')===FALSE) {
        /* it's a single number convert to subnet mask*/
        $sub = $this->convertSubnet($sub);
      } else {
        $sub = explode('.',$sub);
      }
    } else { /* single ip */
      $net = $exp;
      $sub = array(255,255,255,255);
    }
    $ip = explode('.',$sip);
    $net = explode('.',$net);

    /* now do the match */
    $mip[0] = $ip[0] & $sub[0];
    $mip[1] = $ip[1] & $sub[1];
    $mip[2] = $ip[2] & $sub[2];
    $mip[3] = $ip[3] & $sub[3];
    $dip[0] = $net[0] & $sub[0];
    $dip[1] = $net[1] & $sub[1];
    $dip[2] = $net[2] & $sub[2];
    $dip[3] = $net[3] & $sub[3];
    if (($mip[0] == $dip[0]) and ($mip[1] == $dip[1]) and
        ($mip[2] == $dip[2]) and ($mip[3] == $dip[3]))
      return true;
    else
      return false;
  }

  function banned() {
    global $_SERVER;

    $banned = $this->db->read('banned',array(),array('priority'));
    /* now check if the ip has been banned display the banned template */
    foreach ($banned as $row) {
      if ($this->matchIP($_SERVER['REMOTE_ADDR'],$row['ip'])) {
        return $row['access'];
      }
    }
    /* no match has been found */ 
    return 'deny';
  }

  function run($action = '',$step = 0) {
    global $_SERVER;
    global $_SESSION;
    global $_GET;

    $this->mainPage = 'index';

    /* setup the template variable */
    if (!isset($this->config['defaultaction'])) $this->config['defaultaction']='u';

    $this->action= $action=='' ? $this->config['defaultaction']:$action;
    $this->step= $step==0 ?1:$step;

    $this->tpl->assign('action',$this->action);
    $this->tpl->assign('step',$this->step);
    $this->tpl->assign('nextstep',$this->step+1);
    $this->tpl->assign('site',$this->config['site']);
    $this->tpl->assign('script',$_SERVER['PHP_SELF']);
    $this->tpl->assign('page',$this->page);

    if (isset($this->config['multiupload'])) {
      if ($this->config['multiupload']<=0) 
        $this->config['multiupload']=1;
      $this->tpl->assign('multiupload',$this->config['multiupload']);
    } else {
      $this->config['multiupload']=1;
    }
    /* check for banned IP */
    if ($this->banned() != 'allow') {
      $this->log('security','banned','','DENY','');
      $this->page['content'] = $this->fetch('banned');
      $this->page['title']= tr('IP Banned');
      $this->tpl->assign('page',app()->page);
      $this->display($this->mainPage);
      $this->db->free();
      exit;
    }
    /* depending on the acl some actions need authentication others don't */
    if (!isset($this->actions[$this->action])) {
      /* no module can handle this action */
      $this->log('error','none','','NOT FOUND','');
      redirect();
    }
    /* get the handling module */
    $mname = $this->actions[$this->action];
    $m = &$this->modules[$mname];
    $group = $this->user->group();

    if ($this->checkACL($group,$mname,$this->action)!='allow') {
      if ($this->config['defaultaction']==$this->action) {
        /* this is the default action, but the user does not have permissions on it */
        /* check if login is allowed (it should always be */
        if ($this->checkACL($group,'auth','login')!='allow') {
          /* Login is not allowed there is an error, display the default page with a warning */
          $this->log('error','checkACL','','DENY','default action not allowed!!!');
          $this->tpl->assign('user',$this->user->info());
          $this->tpl->assign('langs',$this->langs);
          unset($_SESSION['user']['messages']);
          unset($_SESSION['user']['errors']);
          $this->page['content']=tr('THERE HAS BEEN A PERMISSION ERROR. PLEASE TRY ONE OF THE ALLOWED OPTIONS!'); 
          $this->tpl->assign('page',$this->page);
          $this->display($this->mainPage);
          $this->db->free();
          exit(0);
        } else {
          /* save the requested url */
          redirect('?action=login');
        }
      }
      if ($_SERVER['QUERY_STRING']!='')
        $_SESSION['requested_url']='?'.$_SERVER['QUERY_STRING'];
      redirect();
    } 
    $this->initPlugins();

    $this->initMenu($this->user->loggedin());

    /* now run the module */
    if (isset($m->actions[$this->action][$this->step])) {
      $fun = $m->actions[$this->action][$this->step];
    } else {
      $fun = $m->actions[$this->action][1];
    }
    if (isset($m->page[$this->action])) {
      foreach ($m->page[$this->action] as $k => $v) {
        $this->page[$k] = $v;
      }
    }
    $this->tpl->assign('user',$_SESSION['user']);
    $m->$fun();

    if ($_GET['type']=='ajax') return;

    /* now display the final page */
    $this->tpl->assign('user',$this->user->info());
    $this->tpl->assign('langs',$this->langs);
    unset($_SESSION['user']['messages']);
    unset($_SESSION['user']['errors']);
    $this->tpl->assign('plugins',$this->pluginHTML);
    $this->page['content']=$this->fetch('modules/'.$m->name.'/'.$fun); 
    $this->tpl->assign('page',$this->page);
    $this->display($this->mainPage);
    $this->log('info',$fun,'','ALLOW','');
    $this->db->free();
  }
}

?>
