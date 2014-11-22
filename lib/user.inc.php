<?php

/* User info is stored in the session */

class OpenUploadUser {
  function OpenUploadUser() {
  }

  function init() {
    /* setup the user */
  }

  function logout() {
    global $_SESSION;

    $messages = $_SESSION['user']['messages'];
    $errors = $_SESSION['user']['errors'];
    app()->log('security','authenticate','','ALLOW','User logged out');
    unset($_SESSION['user']);
    $_SESSION['user']['messages'] = $messages;
    $_SESSION['user']['errors'] = $errors;
    redirect('?action=login');
  }

  function loggedin() {
    global $_SESSION;
    if (isset($_SESSION['user']['login']) and $_SESSION['user']['login']!='') {
      return true;
    }
    return false;
  }
  
  function info($field = '') {
    if ($field != '') {
     return $_SESSION['user'][$field];
    } else {
     return $_SESSION['user']; 
    }
  }

  function group() {
    if ($this->info('group')!='')
      $group = $this->info('group');
    else 
      $group = app()->config['register']['nologingroup'];
    return $group;
  }

  function setInfo($name,$value) {
    $_SESSION['user'][$name]=$value;
  }

  function set($user) {
    $_SESSION['user']=$user;
  }
  
  function authenticate() {
    global $_SESSION;
    global $_GET;
    global $_POST;
    
    /* logout if requested */
    if (isset($_GET['logout'])) {
      $this->logout();
    }

    /* if already authenticated return */
    if ($this->loggedin())
      return true;

    // if it's logging in save user and pwd
    if (isset($_POST['username'])) {
      $username = $_POST['username'];
      $password = $_POST['pwd'];
    }

    if ($username != '') {
      // use the default authentication method
      $res = $this->auth->authenticate($username,$password);
      if ($res) {
        $_SESSION['user']['login']=$username;
        /* retrieve user info */
        $_SESSION['user'] = $this->auth->userinfo($username);
        /* make the post not be resent on refresh */
        app()->log('security','authenticate','','ALLOW','User logged in');
        return true;
      } else {
        // set the error message for the login
        app()->error(tr('Login incorrect!'));
        app()->log('security','authenticate','','DENY','Login failed: '.$username);
      }
    }
    return false;
  }
}

?>