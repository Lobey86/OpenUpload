<?php

class AuthModule extends OpenUploadModule {
var $actions = array (
      "login" => array (
        1 => "loginForm",
        2 => "authenticate",
      ),
      "profile" => array (
        1 => "profile",
        2 => "profileedit",
      ),
      "logout" => array (
        1 => "logout",
      ),
      "register" => array (
        1 => "registerForm",
        2 => "registerConfirm",
        3 => "registerEnable",
      ),
    );
var $page;

  function AuthModule() {
    $this->page = array (
      "login" => array (
        "title" => tr("Login"),
      ),
      "register" => array (
        "title" => tr("User registration"),
      ),
      "profile" => array (
        "title" => tr("User options"),
      ),
    );
  }

  function init() {
    if (!app()->user->loggedin()) {
      $this->menu['login']=tr('Login');
    } else {
      if (app()->auth->features['useradmin']=='yes')
        $this->menu['profile']=tr('Preferences');
      $this->menu['logout']=tr('Logout');
    }
    if (app()->auth->features['useradmin']=='yes')
      $this->tpl->assign('register',app()->checkACL(app()->user->group(),'auth','register')=='allow');
  }


  function loginForm() {

    /* disable login option link */
    $this->tpl->assign('login',false);
    $this->page['title']='Login';

    $finfo = array();
    app()->pluginAction('loginForm',$finfo,false);
    //app()->mainPage = 'login';
  }

  function authenticate() {
    if (!app()->user->authenticate()) {
      app()->user->logout();
      return false; /* never reached */
    }
    $finfo = array();
    $result = app()->pluginAction('authenticate',$finfo,true);
    if (!$result) { /* plugins forced a logout */
      app()->user->logout();
      return false; /* never reached */
    } 
    /* authentication was successfull */
    $url = '';
    if (isset($_SESSION['requested_url'])) {
       $url = $_SESSION['requested_url'];
       unset($_SESSION['requested_url']);
    }
    redirect($url);
  }

  function logout() {
    app()->user->logout();
  }

  function registerForm() {
    global $_SESSION;
    global $_GET;
    global $_POST;

    if (app()->auth->features['useradmin']!='yes') {
       app()->log('error','registerForm','','ERROR','Registration not supporte by Auth Module');
       app()->error(tr('Registration is not supported by Auth Module'));
       redirect();
    }

    if (isset($_GET['regid'])) {
      /* confirm registration */
      $_SESSION['user']['regidconfirm']=$_GET['regid'];
      $this->nextStep(3);
    }
    /* ask the plugins if require more options */
    $result = app()->pluginAction('registerForm',$user);
    if (!$result) {
      /* some plugin disabled the registration */
      redirect();
    }
    $this->tpl->assign('register',$_SESSION['register']);
  }

  function registerConfirm() {
    global $_SESSION;
    global $_POST;
    
    if (app()->auth->features['useradmin']!='yes') {
       app()->error(tr('Registration is not supported by Auth Module'));
       redirect();
    }

    if (isset($_POST['registerlogin'])) {
      /* check for the unique login */
      $u = app()->auth->userinfo($_POST['registerlogin']);
      if ($u['login']!='') {
        app()->error(tr('Username already taken, choose a new value'));
        $failed = true;
      } 
      if (strlen($_POST['registerlogin'])<5) {
        app()->error(tr('Login name must be at least 5 characters long!'));
        $failed = true;
      }
      if (ereg_replace('[a-zA-Z0-9_]*','',$_POST['registerlogin'])!='') {
         app()->error(tr('Login name contains an invalid character. Valid vharacters are %1','[a-z] [0-9] [_]'));
        $failed = true;
      }
      if ($_POST['registername']=='') {
        app()->error(tr('Please insert Full Name'));
        $failed = true;
      } 
      if ($_POST['registeremail']=='' or !validEmail($_POST['registeremail'])) {
        app()->error(tr('Please insert a valid e-mail!'));
        $failed = true;
      } 
      if (strlen(trim($_POST['registerpassword']))<5) {
        app()->error(tr('Password must be at least 5 characters long!'));
        $failed = true;
      }
      if ($_POST['registerpassword']!=$_POST['registerrepassword']) {
        app()->error(tr('Passwords do not match! please retype.'));
        $failed = true;
      }
      $user['login'] = $_POST['registerlogin'];
      $user['name'] = htmlentities($_POST['registername']);
      $user['password'] = $_POST['registerpassword'];
      $user['email'] = $_POST['registeremail'];
      $user['lang'] = htmlentities($_POST['registerlang']);
      $user['group_name'] = app()->config['register']['default_group'];
      $user['reg_date']=date('Y-m-d H:i:s');
      $result = app()->pluginAction('registerConfirm',$user);
      $_SESSION['register']=$user;
      unset($_SESSION['register']['password']);
      if (!$result) {
        $failed = true;
      }
      if ($failed) 
        $this->prevStep(1); /* back to registration form */

      if (app()->config['registration']['email_confirm']=='yes') {
        $user['active'] = 0;
        $user['regid']=app()->db->newRandomId('users','regid');
        $subject = tr('[%1] User registration confirmation e-mail',app()->config['site']['title']);
        $this->tpl->assign('reguser',$user);
        $this->tpl->assign('reglink',app()->config['WWW_SERVER'].app()->config['WWW_ROOT'].'/?action=register&regid='.$user['regid']);
        sendMail(app()->config['site']['email'],'noreply',$user['email'],$subject,'modules/auth/emailconfirm');
      } else {
        $user['active'] = 1;
      }
      app()->auth->useradd($user);
    } else {
      $this->prevStep(1); /* back to registration form */
    }
    unset($_SESSION['register']);
    /* ask the plugins if require more options */
    if ($user['active']=='1') {
      app()->message(tr('Registration completed successfully. Have fun!'));
      $_POST['username'] = $user['login'];
      $_POST['pwd'] = $user['password'];
      /* simulate the user login and proceed */
      unset($_SESSION['user']);
      $this->authenticate();
    } else {
      /* display a message */
      $this->tpl->assign('emailconfirm',app()->config['registration']['email_confirm']);
      $this->tpl->assign('moderation',app()->config['registration']['moderation']);
    }
  }

  function registerEnable() {
    global $_SESSION;

    if (app()->auth->features['useradmin']!='yes') {
       app()->error(tr('Registration is not supported by Auth Module'));
       redirect();
    }

    /* if everything is ok register the user */
    if (isset($_SESSION['user']['regidconfirm'])) {
      $user = app()->db->read('users',array('regid' => $_SESSION['user']['regidconfirm']));
      if (count($user)>0) {
        $user = $user[0];
        $user['active']=1;
        $user['regid']=''; /* disable possibility to reactivate it if disabled by the admin */
        app()->db->update('users',$user,array('id' => $user['id']),array('active','regid'));
        app()->log('notice','registerEnable','','OK',$user['login']);
      }
    }
  }

  function profile() {

    if (app()->auth->features['useradmin']!='yes') {
       app()->error(tr('User profile change not supported by Auth Module'));
       redirect();
    }

    $user = app()->user->info();
    $this->tpl->assign('puser',$user);
  }

  function profileedit() {
    global $_POST;

    if (app()->auth->features['useradmin']!='yes') {
       app()->error(tr('User profile change not supported by Auth Module'));
       redirect();
    }
    $user = app()->user->info();
    if (isset($_POST['username'])) {
      /* check for valid values*/
      if ($_POST['username']=='') {
        app()->error(tr('Full Name cannot be empty!'));
        $error = true;
      } else
        $user['name']=$_POST['username'];
      if (!validEmail($_POST['useremail'])) {
        app()->error(tr('Please enter a valid e-mail address!'));
        $error=true;
      }
      $user['lang']=$_POST['userlang'];
      $user['email']=$_POST['useremail'];
      if ($_POST['newpassword']!='') {
        $error = false;
        $pwd = false;
        if (strlen($_POST['newpassword'])<5) {
          app()->error(tr('Password must be at least 5 charaters long!'));
          $error = true;
        } else if (crypt($_POST['oldpassword'],$user['password'])!=$user['password']) {
          app()->error(tr('Old password is wrong!'));
          $error = true;
        } else if ($_POST['newpassword']!=$_POST['confirmpassword']) {
          app()->error(tr('New passwords do not match!'));
          $error = true;
        } else { 
          app()->message(tr('Password has been changed!'));
          $user['password']=$_POST['newpassword'];
          $pwd = true;
        }
      }
      if (!$error) {
        app()->auth->useredit($user,$pwd);
        app()->user->set($user);
        $this->nextStep(1);
      }
    }
    $this->tpl->assign('puser',$user);
  }
}
?>
