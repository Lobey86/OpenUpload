<?php

/**
 * Project:     OpenUpload
 * File:        index.php
 *
 * LICENSE:
 *
 *   Copyright 2008-2009 Alessandro Briosi
 *
 *   This file is part of OpenUpload.
 *
 *   OpenUpload is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   OpenUpload is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with OpenUpload; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @link http://openupload.sf.net/
 * @copyright 2008 Alessandro Briosi
 * @author Alessandro Briosi <tsdogs at briosix dot org>
 * @package OpenUpload
 * @version 0.4
 *
 * 2009-04-16 - Jochen Derwae - www.coaladesign.com
 *   There was a small inconsistency in the html code at the bottom: body start tag was missing, 
 *   head was closed before the styles and the root div wasn't closed
 */


/**
 * Simulate mssql_escape_string
 * @param $string_to_escape
 * @return unknown_type
 */
function mssql_escape_string($string_to_escape) {
  $replaced_string = str_replace("'","''",$string_to_escape);
  return $replaced_string;
} 

if (!defined('__VALID_CALLING_SCRIPT')) die('DIRECT ACCESS IS DENIED');

ob_start();
session_start();

$steps = array (
  1 => array (
    'title' => "Welcome",
    'function' => 'welcome',
  ),
  2 => array (
    'title' => "PHP Setup check",
    'function' => 'setupcheck',
  ),
  3 => array (
    'title' => "Paths",
    'function' => 'paths',
  ),
  4 => array (
    'title' => "Database Type",
    'function' => 'databasetype',
  ),
  5 => array (
    'title' => "Database Options",
    'function' => 'databaseoptions',
  ),
  6 => array (
    'title' => "Application options",
    'function' => 'options',
  ),
  7 => array (
    'title' => "Users",
    'function' => "users",
  ),
  8 => array (
    'title' => "Plugins",
    'function' => "plugins",
  ),
  9 => array (
    'title' => "Database inizialization",
    'function' => "createdb",
  ),
  10 => array (
    'title' => "Save configuration",
    'function' => 'save',
  ),
);

/* DATABASE INITIALIZATION QUERY */

$MYSQL_QUERY = array (
   'dropdb' => 'DROP DATABASE IF EXISTS `%1`',
   'createdb' => 'CREATE DATABASE `%1`',
   'dropuser' => '',
   'createuser' => '',
   'grant' => 'GRANT ALL PRIVILEGES ON %2.* TO "%1"@"localhost"  IDENTIFIED BY "%3"',
   'droptable' => 'DROP TABLE IF EXISTS `%1%2`',
);

$PGSQL_QUERY = array (
   'dropdb' => 'DROP DATABASE "%1"',
   'createdb' => 'CREATE DATABASE "%1"',
   'dropuser' => '',
   'createuser' => '',
   'grant' => '',
   'droptable' => 'DROP TABLE IF EXISTS %1%2',
);

//LCARD
$MSSQL_QUERY = array (
   'dropdb' => 'DROP DATABASE "%1"',
   'createdb' => 'CREATE DATABASE "%1"',
   'dropuser' => '',
   'createuser' => '',
   'grant' => '',
   'droptable' => 'IF EXISTS (SELECT name FROM sysobjects WHERE name = \'%1%2\' AND xtype=\'U\' ) DROP TABLE %1%2',
);
//LCARD

$DB_STRUCTURE = array (
  'acl' => array (
     'fields' => array (
        'id' => array ( 'type' => 'int', 'size' => 11, 'extra' => 'auto_increment', 'null' => 'NOT NULL', ),
        'module' => array ( 'type' => 'char', 'size' => 100, 'extra' => '', 'null' => 'NOT NULL', ),
        'action' => array ( 'type' => 'char', 'size' => 100, 'extra' => '', 'null' => 'NOT NULL', ),
        'group_name' => array ( 'type' => 'char', 'size' => 50, 'extra' => '', 'null' => 'NOT NULL', ),
        'access' => array ( 'type' => 'char', 'size' => 10, 'extra' => '', 'null' => 'NOT NULL', ),
     ),
     'keys' => array (
        'id' => array ( 'primary' => true, 'fields' => array ('id'), ),
     ),
  ),
  'banned' => array (
     'fields' => array (
        'id' => array ( 'type' => 'int', 'size' => 11, 'extra' => 'auto_increment', 'null' => 'NOT NULL', ),
        'ip' => array ( 'type' => 'char', 'size' => 50, 'extra' => '', 'null' => 'NOT NULL', ),
        'access' => array ( 'type' => 'char', 'size' => 50, 'extra' => '', 'null' => 'NOT NULL', ),
        'priority' => array ( 'type' => 'int', 'size' => 11, 'extra' => '', 'null' => 'NOT NULL', ),
     ),
     'keys' => array (
        'id' => array ( 'primary' => true, 'fields' => array ('id'), ),
     ),
  ),
  'files' => array (
     'fields' => array (
        'id' => array ( 'type' => 'char', 'size' => 100, 'extra' => '', 'null' => 'NOT NULL', ),
        'name' => array ( 'type' => 'char', 'size' => 200, 'extra' => '', 'null' => 'NOT NULL', ),
        'mime' => array ( 'type' => 'char', 'size' => 200, 'extra' => '', 'null' => 'NOT NULL', ),
        'description' => array ( 'type' => 'text', 'size' => 0, 'extra' => '', 'null' => 'NOT NULL', ),
        'size' => array ( 'type' => 'int', 'size' => 12, 'extra' => '', 'null' => 'NOT NULL', ),
        'remove' => array ( 'type' => 'char', 'size' => 100, 'extra' => '', 'null' => 'NOT NULL', ),
        'user_login' => array ( 'type' => 'char', 'size' => 100, 'extra' => '', 'null' => 'NOT NULL', ),
        'ip' => array ( 'type' => 'char', 'size' => 40, 'extra' => '', 'null' => 'NOT NULL', ),
        'upload_date' => array ( 'type' => 'datetime', 'size' => 0, 'extra' => '', 'null' => 'NOT NULL', ),
     ),
     'keys' => array (
        'id' => array ( 'primary' => true, 'fields' => array ('id'), ),
     ),
  ), 
  'file_options' => array (
     'fields' => array (
        'id' => array ( 'type' => 'int', 'size' => 20, 'extra' => 'auto_increment', 'null' => 'NOT NULL', ),
        'file_id' => array ( 'type' => 'char', 'size' => 100, 'extra' => '', 'null' => 'NOT NULL', ),
        'module' => array ( 'type' => 'char', 'size' => 50, 'extra' => '', 'null' => 'NOT NULL', ),
        'name' => array ( 'type' => 'char', 'size' => 50, 'extra' => '', 'null' => 'NOT NULL', ),
        'value' => array ( 'type' => 'char', 'size' => 200, 'extra' => '', 'null' => 'NOT NULL', ),
     ),
     'keys' => array (
        'id' => array ( 'primary' => true, 'fields' => array ('id'), ),
        'file_id' => array ( 'primary' => false, 'unique' => false, 'fields' => array('file_id') ),
     ),
  ),
  'groups' => array (
     'fields' => array (
        'name' => array ( 'type' => 'char', 'size' => 50, 'extra' => '', 'null' => 'NOT NULL', ),
        'description' => array ( 'type' => 'char', 'size' => 250, 'extra' => '', 'null' => '', ),
     ),
     'keys' => array (
        'name' => array ( 'primary' => true, 'fields' => array('name'), ),
     ),
  ),
  'langs' => array (
     'fields' => array (
        'id' => array ( 'type' => 'char', 'size' => 10, 'extra' => '', 'null' => 'NOT NULL', ),
        'name' => array ( 'type' => 'char', 'size' => 100, 'extra' => '', 'null' => 'NOT NULL', ),
        'locale' => array ( 'type' => 'char', 'size' => 10, 'extra' => '', 'null' => 'NOT NULL', ),
        'browser' => array ( 'type' => 'char', 'size' => 200, 'extra' => '', 'null' => '', ),
        'charset' => array ( 'type' => 'char', 'size' => 50, 'extra' => '', 'null' => '', ),
        'active' => array ( 'type' => 'int', 'size' => 1, 'extra' => '', 'null' => '', 'default' => '1'),
     ),
     'keys' => array (
        'id' => array ( 'primary' => true, 'fields' => array('id'), ),
     ),
  ),
  'plugin_acl' => array (
     'fields' => array (
        'id' => array ( 'type' => 'int', 'size' => 10, 'extra' => 'auto_increment', 'null' => 'NOT NULL', ),
        'group_name' => array ( 'type' => 'char', 'size' => 50, 'extra' => '', 'null' => 'NOT NULL', ),
        'plugin' => array ( 'type' => 'char', 'size' => 100, 'extra' => '', 'null' => 'NOT NULL', ),
        'access' => array ( 'type' => 'char', 'size' => 10, 'extra' => '', 'null' => 'NOT NULL', ),
     ),
     'keys' => array (
        'id' => array ( 'primary' => true, 'fields' => array('id'), ),
     ),
  ),
  'plugin_options' => array (
     'fields' => array (
        'id' => array ( 'type' => 'int', 'size' => 10, 'extra' => 'auto_increment', 'null' => 'NOT NULL', ),
        'plugin' => array ( 'type' => 'char', 'size' => 100, 'extra' => '', 'null' => 'NOT NULL', ),
        'group_name' => array ( 'type' => 'char', 'size' => 50, 'extra' => '', 'null' => 'NOT NULL', ),
        'name' => array ( 'type' => 'char', 'size' => 100, 'extra' => '', 'null' => 'NOT NULL', ),
        'value' => array ( 'type' => 'text', 'size' => 0, 'extra' => '', 'null' => 'NOT NULL', ),
     ),
     'keys' => array (
        'id' => array ( 'primary' => true, 'fields' => array('id'), ),
     ),
  ),
  'users' => array (
     'fields' => array (
        'id' => array ( 'type' => 'int', 'size' => 10, 'extra' => 'auto_increment', 'null' => 'NOT NULL', ),
        'login' => array ( 'type' => 'char', 'size' => 100, 'extra' => '', 'null' => 'NOT NULL', ),
        'password' => array ( 'type' => 'char', 'size' => 100, 'extra' => '', 'null' => 'NOT NULL', ),
        'name' => array ( 'type' => 'char', 'size' => 200, 'extra' => '', 'null' => 'NOT NULL', ),
        'group_name' => array ( 'type' => 'char', 'size' => 50, 'extra' => '', 'null' => 'NOT NULL', 'default' => 'registered'),
        'email' => array ( 'type' => 'char', 'size' => 250, 'extra' => '', 'null' => 'NOT NULL', ),
        'lang' => array ( 'type' => 'char', 'size' => 10, 'extra' => '', 'null' => 'NOT NULL', 'default' => 'en'),
        'reg_date' => array ( 'type' => 'datetime', 'size' => 0, 'extra' => '', 'null' => 'NOT NULL', ),
        'regid' => array ( 'type' => 'char', 'size' => 50, 'extra' => '', 'null' => 'NOT NULL', 'default' => ''),
        'active' => array ( 'type' => 'int', 'size' => 1, 'extra' => '', 'null' => 'NOT NULL', ),
     ),
     'keys' => array (
        'id' => array ( 'primary' => true, 'fields' => array('id'), ),
        'login' => array ( 'primary' => false, 'unique' => true, 'fields' => array('id'), ),
     ),
  ),
  'activitylog' => array (
     'fields' => array (
        'id' => array ( 'type' => 'int', 'size' => 20, 'extra' => 'auto_increment', 'null' => 'NOT NULL', ),
        'level' => array ( 'type' => 'char', 'size' => 20, 'extra' => '', 'null' => 'NOT NULL', ),
        'log_time' => array ( 'type' => 'datetime', 'size' => 0, 'extra' => '', 'null' => 'NOT NULL', ),
        'ip' => array ( 'type' => 'char', 'size' => 20, 'extra' => '', 'null' => 'NOT NULL', ),
        'user_login' => array ( 'type' => 'char', 'size' => 100, 'extra' => '', 'null' => 'NOT NULL', ),
        'module' => array ( 'type' => 'char', 'size' => 50, 'extra' => '', 'null' => 'NOT NULL', ),
        'action' => array ( 'type' => 'char', 'size' => 50, 'extra' => '', 'null' => 'NOT NULL', ),
        'realaction' => array ( 'type' => 'char', 'size' => 50, 'extra' => '', 'null' => '', ),
        'plugin' => array ( 'type' => 'char', 'size' => 50, 'extra' => '', 'null' => '', ),
        'result' => array ( 'type' => 'char', 'size' => 100, 'extra' => '', 'null' => '', ),
        'moreinfo' => array ( 'type' => 'text', 'size' => 0, 'extra' => '', 'null' => '', ),
    ),
     'keys' => array (
        'id' => array ( 'primary' => true, 'fields' => array('id'), ),
     ),
  ),
);


$DB_DATA = array (
  'base' => array (
    'acl' => array (
      array('id' => 1, 'module' => '*', 'action' => '*', 'group_name' => '%3', 'access' => 'allow' ),
      array('id' => 2, 'module' => 'admin', 'action' => '*', 'group_name' => '%3', 'access' => 'allow' ),
      array('id' => 3, 'module' => 'admin', 'action' => '*', 'group_name' => '*', 'access' => 'deny' ),
      array('id' => 4, 'module' => 'auth', 'action' => 'login', 'group_name' => '%5', 'access' => 'allow' ),
    ),
    'groups' => array (
      array ('name' => '%3' ,'description' => 'Administrators group' ),
      array ('name' => '%4' ,'description' => 'Registered Users' ),
      array ('name' => '%5' ,'description' => 'Unregistered users' ),
    ),
    'banned' => array (
      array ( 'id' => 1, 'ip' => '127.0.0.1', 'access' => 'allow', 'priority' => 1),
      array ( 'id' => 2, 'ip' => '0.0.0.0/0', 'access' => 'allow', 'priority' => 9999999),
    ),
    'langs' => array (
      array ('id' => 'en', 'name' => 'English', 'locale' => 'en_EN', 'browser' => '[en];[en-EN];[en-US]', 'charset' => 'utf-8', 'active' => 1),
      array ('id' => 'it', 'name' => 'Italiano', 'locale' => 'it_IT.utf8', 'browser' => '[it];[it-IT]', 'charset' => 'utf-8', 'active' => 1),
      array ('id' => 'fr', 'name' => 'Français', 'locale' => 'fr_FR.utf8', 'browser' => '[fr];[fr-FR]', 'charset' => 'utf-8', 'active' => 1),
      array ('id' => 'de', 'name' => 'Deutsch', 'locale' => 'de_DE.utf8', 'browser' => '[de];[de-DE]', 'charset' => 'utf-8', 'active' => 1),
      array ('id' => 'pt', 'name' => 'Português', 'locale' => 'pt_BR.utf8', 'browser' => '[pt];[pt-BR]', 'charset' => 'utf-8', 'active' => 1),
      array ('id' => 'zh_CN', 'name' => '中文', 'locale' => 'zh_CN.utf8', 'browser' => '[zh];[zh-CN]', 'charset' => 'utf-8', 'active' => 1),
    ),
    'users' => array (
      array ( 'id' => 1, 'login' => '%1', 'password' => '%2', 'name' => 'Administrator', 'group_name' => '%3', 'email' => '%6',
              'lang' => 'en', 'reg_date' => '2009-01-01', 'regid' => '', 'active' => 1 ),
    ),
    'plugin_acl' => array (
      array ( 'id' => '1', 'group_name' => '%3', 'plugin' => 'password', 'access' => 'enable'),
      array ( 'id' => '2', 'group_name' => '%3', 'plugin' => 'captcha', 'access' => 'enable'),
      array ( 'id' => '3', 'group_name' => '%3', 'plugin' => 'email', 'access' => 'enable'),
    ),
    'plugin_options' => array (
      array ( 'id' => '1', 'plugin' => 'mimetypes', 'group_name' => '%5', 'name' => 'message', 'value' => 'Pdf, Jpeg'),
      array ( 'id' => '2', 'plugin' => 'mimetypes', 'group_name' => '%5', 'name' => 'allowed', 'value' => 'application/pdf'."\n".'image/jpeg'),
      array ( 'id' => '3', 'plugin' => 'expire', 'group_name' => '*', 'name' => 'days', 'value' => '30'),
      array ( 'id' => '4', 'plugin' => 'upgrade', 'group_name' => '*', 'name' => 'version', 'value' => '0.4.2'),
    ),
  ),
  'mode_1' => array ( /* Private mode */
    'acl' => array (
      array('id' => 5, 'module' => 'auth', 'action' => 'register', 'group_name' => '*', 'access' => 'deny' ),
      array('id' => 6, 'module' => 'auth', 'action' => '*', 'group_name' => '%5', 'access' => 'deny' ),
      array('id' => 7, 'module' => 'auth', 'action' => '*', 'group_name' => '*', 'access' => 'allow' ),
      array('id' => 8, 'module' => 'files', 'action' => 'd', 'group_name' => '%5', 'access' => 'allow' ),
      array('id' => 9, 'module' => 'files', 'action' => 'g', 'group_name' => '%5', 'access' => 'allow' ),
      array('id' => 10, 'module' => 'files', 'action' => '*', 'group_name' => '%5', 'access' => 'deny' ),
      array('id' => 11, 'module' => 'files', 'action' => '*', 'group_name' => '*', 'access' => 'allow' ),
    ),
    'plugin_acl' => array (
      array ( 'id' => 4, 'group_name' => '%4', 'plugin' => 'password', 'access' => 'enable'),
      array ( 'id' => 5, 'group_name' => '%4', 'plugin' => 'captcha', 'access' => 'enable'),
      array ( 'id' => 6, 'group_name' => '%4', 'plugin' => 'email', 'access' => 'enable'),
    ),
  ),
  'mode_2' => array ( /* Restricted mode */
    'acl' => array (
      array('id' => 5, 'module' => 'auth', 'action' => 'register', 'group_name' => '%5', 'access' => 'allow' ),
      array('id' => 6, 'module' => 'auth', 'action' => '*', 'group_name' => '%5', 'access' => 'deny' ),
      array('id' => 7, 'module' => 'auth', 'action' => '*', 'group_name' => '*', 'access' => 'allow' ),
      array('id' => 8, 'module' => 'files', 'action' => 'd', 'group_name' => '%5', 'access' => 'allow' ),
      array('id' => 9, 'module' => 'files', 'action' => 'g', 'group_name' => '%5', 'access' => 'allow' ),
      array('id' => 10, 'module' => 'files', 'action' => '*', 'group_name' => '%5', 'access' => 'deny' ),
      array('id' => 11, 'module' => 'files', 'action' => '*', 'group_name' => '*', 'access' => 'allow' ),
    ),
    'plugin_acl' => array (
      array ( 'id' => 4, 'group_name' => '%4', 'plugin' => 'password', 'access' => 'enable'),
      array ( 'id' => 5, 'group_name' => '%4', 'plugin' => 'captcha', 'access' => 'enable'),
      array ( 'id' => 6, 'group_name' => '%4', 'plugin' => 'email', 'access' => 'enable'),
    ),
  ),
  'mode_3' => array ( /* Service mode */
    'acl' => array (
      array('id' => 5, 'module' => 'auth', 'action' => 'register', 'group_name' => '%5', 'access' => 'allow' ),
      array('id' => 6, 'module' => 'auth', 'action' => '*', 'group_name' => '%5', 'access' => 'deny' ),
      array('id' => 7, 'module' => 'auth', 'action' => 'register', 'group_name' => '*', 'access' => 'deny' ),
      array('id' => 8, 'module' => 'auth', 'action' => '*', 'group_name' => '*', 'access' => 'deny' ),
      array('id' => 9, 'module' => 'files', 'action' => 'l', 'group_name' => '%5', 'access' => 'deny' ),
      array('id' => 10, 'module' => 'files', 'action' => '*', 'group_name' => '*', 'access' => 'allow' ),
    ),
    'plugin_acl' => array (
      array ( 'id' => 4, 'group_name' => '%4', 'plugin' => 'password', 'access' => 'enable'),
      array ( 'id' => 5, 'group_name' => '%4', 'plugin' => 'captcha', 'access' => 'enable'),
      array ( 'id' => 6, 'group_name' => '%4', 'plugin' => 'email', 'access' => 'enable'),
      array ( 'id' => 7, 'group_name' => '%5', 'plugin' => 'mimetypes', 'access' => 'enable'),
      array ( 'id' => 8, 'group_name' => '%5', 'plugin' => 'captcha', 'access' => 'enable'),
      array ( 'id' => 9, 'group_name' => '%5', 'plugin' => 'password', 'access' => 'enable'),
    ),
  ),
  'mode_4' => array ( /* Public mode */
    'acl' => array (
      array('id' => 5, 'module' => 'auth', 'action' => '*', 'group_name' => '*', 'access' => 'deny' ),
      array('id' => 6, 'module' => 'files', 'action' => 'l', 'group_name' => '%5', 'access' => 'deny' ),
      array('id' => 7, 'module' => 'files', 'action' => '*', 'group_name' => '*', 'access' => 'allow' ),
    ),
    'plugin_acl' => array (
      array ( 'id' => 4, 'group_name' => '%5', 'plugin' => 'password', 'access' => 'enable'),
      array ( 'id' => 5, 'group_name' => '%5', 'plugin' => 'captcha', 'access' => 'enable'),
      array ( 'id' => 6, 'group_name' => '%5', 'plugin' => 'email', 'access' => 'enable'),
    ),
  ),
  
);
/* try to guess the appropriate settings from the $_SERVER */
$step = (isset($_GET['step']))?$_GET['step']:1;
$step = (isset($_POST['step']))?$_POST['step']:$step;

if ($step =='') $step = 1;

$path = 'templates/default';
if (defined('__NOT_MAIN_SCRIPT')) {
  $path = 'www/'.$path;
}

$CONFIG = $_SESSION['config'];

/********************* SUPPORT FUNCTIONS *****************************/

function msg($str,$type = '') {
  global $path;
  echo '<div id="message">';
  if ($type != '') {
    echo '<img src="'.$path.'/img/setup/'.$type.'.png"> ';
  }
  echo $str.'</div>';
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
  sort($result);
  closedir($dir);
  return $result;
}

/************************ SETUP FUNCTIONS ********************/

function welcome($step) {
  global $_POST;
  if (isset($_POST['step'])) {
    return $step+1;
  }
?>
<div id="message">
Now to get started using OpenUpload you need to enter a few setup information...
</div>
<div id="message">
Click on the Next button to proceed installing OpenUpload.
</div>
<form method="POST" action="index.php">
<input type="hidden" name="step" value="<?php echo $step; ?>">
<div><input type="submit" value="Next &gt;&gt;"></div>
</form>
<?php
  return $step;
}

function setupcheck($step) {
  global $_POST;
  global $_SESSION;

  $message = 'Let\'s check your php installation';
  if (isset($_POST['check'])) {
    /* we do some php installation checks */
    echo '<div id="message"><b>PHP INI Settings</b></div>';
    if (ini_get('file_uploads') == 1) {
      msg('File Uploads are enabled','ok');
    } else {
      msg('File Uploads are disable','fail');
      msg('Please check your php.ini setting: file_uploads');
    }
    $msg = 'upload_max_filesize value is set to ';
    msg($msg.': '.ini_get('upload_max_filesize'));
    $msg = 'post_max_size value is set to ';
    msg($msg.': '.ini_get('post_max_size'));
    if (ini_get('register_globals') == 0) {
      msg('Register globals disabled','ok');
    } else {
      msg('Register globals enabled','fail');
      msg('Though the application should work with this set please consider disabling it');
    }
    echo '<div id="message"><b>Optional Values</b></div>';
    if (ini_get('magic_quotes_gpc')) {
      msg('Magic Quotes: enabled','fail');
      msg('It is strongly suggested to disable magic_quotes (which are deprecated now!)');
    } else {
      msg('Magic Quotes: disabled','ok');
    }
    if (function_exists('finfo_open')) {
      msg('Fileinfo extension: installed','ok');
    } else {
      if (function_exists('mime_content_type')) {
        msg('mime type php function: available','ok');
      } else {
        msg('mime type handling: not available','fail');
        msg('This could lead problems with bogus browsers!');
      }      
      msg('For correct mime types handling it\'s suggested to install the Fileinfo');
    }
    if (function_exists('mysql_connect')) {
      msg('MYSQL Support: exsists','ok');
    } else {
      msg('MYSQL Support: not found','fail');
      msg('MYSQL is needed if you plan to use mysql');
    }
    if (function_exists('pg_connect')) {
      msg('POSTGRESQL Support: exsists','ok');
    } else {
      msg('POSTGRESQL Support: not found','fail');
      msg('POSTGRESQL is needed if you plan to use postgresql');
    }
    //LCARD - must config PHP.INI
    if (function_exists('mssql_connect')) {
      msg('MSSQL Support: exsists','ok');
    } else {
      msg('MSSQL Support: not found','fail');
      msg('MSSQL is needed if you plan to use mssql');
    }
    //LCARD
    if (function_exists('ldap_connect')) {
      msg('LDAP Support: exsists','ok');
    } else {
      msg('LDAP Support: not found','fail');
      msg('LDAP is needed for LDAP and AD support');
    }
    if (function_exists('imagecreate')) {
      msg('GD Support: exsists','ok');
    } else {
      msg('GD Support: not found','fail');
      msg('GD is needed for captcha plugin');
    }
    echo '<div id="message"><b>Upload progress traking (only one is needed)</b></div>';
    $progress_values = array('none');
    if (function_exists('uploadprogress_get_info')) {
      msg('UploadProgress Support: exsists','ok');
      $progress_values[]='uploadprogress';
    } else {
      msg('UploadProgress: not found','fail');
    }
    if (function_exists('apc_fetch')) {
      $msg = 'APC Support: exsists';
      if (ini_get('apc.enabled')) {
        $msg .= ', Enabled: yes';
        if (ini_get('apc.rfc1867')) {
          $progress_values[]='apc';
          msg($msg.', rfc1867 enabled: yes','ok');
        } else {
          msg($msg.', rfc1867 enabled: NO','fail');
        }
      } else {
        msg($msg.', Enabled: NO','fail');
      }
    } else {
      msg('APC: not found','fail');
    }
    echo '<hr>';
    $_SESSION['progress_values']=$progress_values;
    $checked = true;
    $message ='If everything looks good proceed!';
  }

  if (isset($_POST['proceed'])) {
    return $step+1;
  }
?>
<div id="message">
<?php echo $message; ?>
</div>
<form method="POST" action="index.php">
<input type="hidden" name="step" value="<?php echo $step; ?>">
<?php if ($checked) { ?>
<input type="submit" name="check" value="Check Again">
<input type="submit" name="proceed" value="Next &gt;&gt;">
<?php } else { ?>
<input type="submit" name="check" value="Check">
<?php } ?>
</form>
<?php
}

/* INSTALLATION AND WWW PATHS */
function guessPaths($CONFIG) {
  global $_SERVER;

echo 'guessing';
  $srvname = $_SERVER['SERVER_NAME']; /* localhost */
  $srvport = $_SERVER['SERVER_PORT']; /* 80 or 443 */

  $http = 'http://';
  if ($srvport == '443') {
    $http = 'https://';
    $srvport = '';
  }
  if ($srvport == '80') 
    $srvport = '';

  $script = $_SERVER['SCRIPT_FILENAME']; /* full path to script */
  /* remove index.php from script */
  if (strpos($script,'index.php')!==FALSE)
    $script = substr($script,0,strpos($script,'index.php')-1);

  if (!defined('__NOT_MAIN_SCRIPT')) {
    if (strpos($script,'/www')==strlen($script)-4) {
      $script = substr($script,0,strlen($script)-4);
    }
  }

  $uri = $_SERVER['REQUEST_URI'];

  if (strpos($uri,'index.php')!==FALSE)
    $uri = substr($uri,0,strpos($uri,'index.php')-1);

  if (defined('__NOT_MAIN_SCRIPT'))
    $uri .= '/www';

  $CONFIG['WWW_SERVER']=$http.$srvname.($srvport!=''?$srvport:'');
  $CONFIG['WWW_ROOT']=$uri;
  $CONFIG['INSTALL_ROOT']=$script;
  $CONFIG['DATA_PATH']=$script.'/data';
  return $CONFIG;
}

function paths($step) {
  global $_POST;
  global $CONFIG;
  global $_SESSION;

  if (!isset($_POST['install_root'])) {
    if (!isset($CONFIG['INSTALL_ROOT'])) {
      $CONFIG = guessPaths($CONFIG);
    }
  } else {
    $CONFIG['WWW_SERVER']=$_POST['www_server'];
    $CONFIG['WWW_ROOT']=$_POST['www_root'];
    $CONFIG['INSTALL_ROOT']=$_POST['install_root'];
    $CONFIG['DATA_PATH']=$_POST['data_path'];

    /* now check for correctness of the settings */
    /* if ok go to next step */
    return $step+1;
  }

  /* now I need to display and ask confirmation of the values */
?>
<div id="message">
Here you must specify the paths of your installation.<br />
Guessed values might be wrong on some circumstances so please make sure they are ok.
</div>
<form method="POST" action="index.php">
<input type="hidden" name="step" value="<?php echo $step; ?>">
<table border="0">
<TR><TD>Install PATH:<br />(Where lib,templates,etc are)</TD>
    <TD><input type="text" name="install_root" size="50" value="<?php echo $CONFIG['INSTALL_ROOT']; ?>"></TD></TR>
<TR><TD>WWW Server URL:</TD>
    <TD><input type="text" name="www_server" size="50" value="<?php echo $CONFIG['WWW_SERVER']; ?>"></TD></TR>
<TR><TD>WWW Root Path <br />(where setup.php is accessible from the web):</TD>
    <TD><input type="text" name="www_root" size="50" value="<?php echo $CONFIG['WWW_ROOT']; ?>"></TD></TR>
<TR><TD>DATA PATH:</TD>
    <TD><input type="text" name="data_path" size="50" value="<?php echo $CONFIG['DATA_PATH']; ?>"></TD></TR>
<TR><TD colspan="2"><input type="submit" value="Next &gt;&gt;"></TD></TR>
</table>
</form>
<?php
  return $step;
}

/* DATABASE SETUP */

function databasetype($step) {
  global $CONFIG;
  global $_POST;

  if (isset($_POST['dbtype'])) {
    $CONFIG['database']['type']=$_POST['dbtype'];
    if ($CONFIG['database']['type']=='txt') {
      $CONFIG['database']['rootdir']=$CONFIG['INSTALL_ROOT'].'/txtdb';
      unset($CONFIG['database']['host']);
      unset($CONFIG['database']['user']);
      unset($CONFIG['database']['password']);
      unset($CONFIG['database']['name']);
    } else {
      unset($CONFIG['database']['rootdir']);
      if (!isset($CONFIG['database']['host'])) {
        $CONFIG['database']['host'] = 'localhost';
        $CONFIG['database']['user'] = '';
        $CONFIG['database']['password'] = '';
        $CONFIG['database']['name'] = 'openupload';
      }
    }
    return $step+1;
  }
  $db = listModules($CONFIG['INSTALL_ROOT'].'/lib/modules/db');
?>
<form method="POST" action="index.php">
<input type="hidden" name="step" value="<?php echo $step; ?>">
<table border="0">
<TR><TD>Database Type:</TD>
<td><select name='dbtype'><option value="">-- Select one --</option>
<?php foreach ($db as $d) {
  $selected = $CONFIG['database']['type']==$d?'selected':'';
  echo '<option value="'.$d.'" '.$selected.'>'.$d.'</option>';
} ?>
</select></td>
</TR>
<TR><TD colspan="2"><input type="submit" value="Next &gt;&gt;"></TD></TR>
</table>
</form>
<?php
}


function databaseoptions($step) {
  global $CONFIG;
  global $_POST;
  global $_SESSION;

  if (isset($_POST['dbhost'])) {
    $CONFIG['database']['host']=$_POST['dbhost'];
    $CONFIG['database']['user']=$_POST['dbusername'];
    if ($_POST['dbpassword']!='')
      $CONFIG['database']['password']=$_POST['dbpassword'];
    $CONFIG['database']['name']=$_POST['dbname'];
    $CONFIG['database']['prefix']=$_POST['dbprefix'];
    $_SESSION['options']['rootuser']=$_POST['rootuser'];
    if ($_POST['rootpassword']!='')
      $_SESSION['options']['rootpassword']=$_POST['rootpassword'];
    $_SESSION['options']['newdb']=$_POST['newdb'];
    $_SESSION['options']['newuser']=$_POST['newuser'];
    $_SESSION['options']['populate']=$_POST['populate'];
    if (isset($_POST['test'])) {
      if ($_SESSION['options']['newdb']==1) {
        $user = $_SESSION['options']['rootuser'];
        $pwd = $_SESSION['options']['rootpassword'];
        if ($CONFIG['database']['type']=='mysql')
          $dbn = 'mysql';
        else 
          $dbn = 'postgres';
      } else {
        $user = $CONFIG['database']['user'];
        $pwd = $CONFIG['database']['password'];
        $dbn = $CONFIG['database']['name'];
      }
      if (dbconnect($CONFIG['database']['host'],$user,$pwd,$dbn,true)) {
        msg('Database connection SUCCESSFULL.','ok');
      } else {
        msg('Database connection failed ','fail');
      }
    } else {
      /* check the values */
      return $step+1;
    }
  }
  //TODO: ask the db class for parameters
?>
<form method="POST" action="index.php">
<input type="hidden" name="step" value="<?php echo $step; ?>">
<table border="0">
<TR><TD>Database Type:</TD>
    <td><?php echo $CONFIG['database']['type']; ?></td></tr>
<?php if ($CONFIG['database']['type']=='txt') { ?>
<TR><TD>DB File Path:</TD><TD><input type="text" size="50" name="dbrootdir" value="<?php echo $CONFIG['database']['rootdir']; ?>"></TD></TR>
<TR><TD colspan="2"><input type="submit" value="Next &gt;&gt;"></TD></TR>
<?php } else { ?>
<TR><TD>Host:</TD><TD><input type="text" size="30" name="dbhost" value="<?php echo $CONFIG['database']['host']; ?>"></TD></TR>
<TR><TD>Username:</TD><TD><input type="text" size="30" name="dbusername" value="<?php echo $CONFIG['database']['user']; ?>"></TD></TR>
<TR><TD>Password:</TD><TD><input type="password" size="30" name="dbpassword" value=""></TD></TR>
<TR><TD>DB Name:</TD><TD><input type="text" size="30" name="dbname" value="<?php echo $CONFIG['database']['name']; ?>"></TD></TR>
<TR><TD>Table prefix:</TD><TD><input type="text" size="30" name="dbprefix" value="<?php echo $CONFIG['database']['prefix']; ?>"></TD></TR>
<TR><TD>Create the database?</TD><TD><input type="checkbox" name="newdb" value="1" <?php if ($_SESSION['options']['newdb']==1) echo 'checked'; ?>></TD></TR>
<TR><TD>Also create user?</TD><TD><input type="checkbox" name="newuser" value="1" <?php if ($_SESSION['options']['newuser']==1) echo 'checked'; ?>></TD></TR>
<TR><TD>DB Admin user:</TD><TD><input type="text" name="rootuser" value="<?php echo $_SESSION['options']['rootuser']; ?>"></TD></TR>
<TR><TD>DB Admin password:</TD><TD><input type="password" name="rootpassword" value=""></TD></TR>
<TR><TD>Populate database</TD><TD>
<select name="populate">
<?php $opt = array("No","Structure only","Base System data","Private mode","Restricted mode","Service mode","Public mode");
  foreach ($opt as $k => $v) {
    echo '<option value="'.$k.'" '.($k==$_SESSION['options']['populate']?'selected':'').'>'.$v.'</option>';
  }
?>
</select>
</TD></TR>
<TR><TD colspan="2"><input type="submit" name="test" value="Test connection"> <input type="submit" value="Next &gt;&gt;"></TD></TR>
<?php } ?>
</table>
</form>
<?php
}

function options($step) {
  global $_POST;
  global $CONFIG;
  global $_SESSION;

  $tr = listModules($CONFIG['INSTALL_ROOT'].'/lib/modules/tr');
  $auth = listModules($CONFIG['INSTALL_ROOT'].'/lib/modules/auth');
  $templates = listModules($CONFIG['INSTALL_ROOT'].'/templates','');

  if (isset($_POST['translator'])) {
    $error = false;
    $CONFIG['translator']=$_POST['translator'];
    $CONFIG['auth']=$_POST['auth'];
    $CONFIG['defaultlang']=$_POST['defaultlang'];
    $CONFIG['site']['title']=$_POST['sitetitle'];
    $CONFIG['site']['webmaster']= $_POST['webmaster'];
    $CONFIG['site']['email']= $_POST['email'];
    $CONFIG['site']['template'] = $_POST['template'];
    $CONFIG['site']['footer']=str_replace('\"','"',$_POST['sitefooter']);
    $CONFIG['registration']['email_confirm']=isset($_POST['confirmregistration'])?$_POST['confirmregistration']:'no';
    $CONFIG['max_upload_size']=$_POST['max_upload_size'];
    $CONFIG['use_short_links']=isset($_POST['use_short_links'])?$_POST['use_short_links']:'no';
    $CONFIG['id_max_length']=$_POST['id_max_length'];
    $CONFIG['id_use_alpha']=isset($_POST['id_use_alpha'])?$_POST['id_use_alpha']:'no';
    $CONFIG['max_download_time']=$_POST['max_download_time'];
    $CONFIG['multiupload']=$_POST['multiupload'];
    $CONFIG['allow_unprotected_removal']=$_POST['allow_unprotected_removal'];
    $CONFIG['progress']=$_POST['progress'];
    $CONFIG['logging']['enabled']=isset($_POST['logging'])?'yes':'no';
    $CONFIG['logging']['db_level']=$_POST['log_db_level'];
    $CONFIG['logging']['syslog_level']=$_POST['log_syslog_level'];

    if ($CONFIG['translator']=='') {
      $error = true;
      msg('Please select a translator','fail');
    }
    if ($CONFIG['auth']=='') {
      $error = true;
      msg('Please select an authentication module','fail');
    }
    if ($CONFIG['site']['webmaster']=='') {
      $error = true;
      msg('Please insert a webmaster e-mail address','fail');
    }
    if ($CONFIG['site']['email']=='') {
      $error = true;
      msg('Please insert a site e-mail address','fail');
    }
    if ($CONFIG['max_upload_size']=='') {
      $error = true;
      msg('Please insert a maximum default upload size','fail');
    }
    if ($CONFIG['max_download_time']=='') {
      $error = true;
      msg('Please insert a maximum download time','fail');
    }
    if ($CONFIG['id_max_length']=='') {
      $error = true;
      msg('Please insert the IDs length','fail');
    }
    if ($CONFIG['multiupload']<1) {
      $error = true;
      msg('Please insert a max number of uploaded files per upload','fail');
    }
    if ($CONFIG['progress']=='') {
      $error = true;
      msg('Please select a method to trak the uploaded files','fail');
    }
    if (!$error) {
      return $step+1;
    }
  } else if (!isset($CONFIG['site']['title'])) {
    /* init default values */
    $CONFIG['translator']='phparray';
    $CONFIG['auth']='default';
    $CONFIG['defaultlang']='en';
    $CONFIG['site']['title']='Open Upload';
    $CONFIG['site']['webmaster']= '';
    $CONFIG['site']['email']= '';
    $CONFIG['site']['footer']='<a href="http://openupload.sf.net">Open Upload</a> - Created by Alessandro Briosi &copy; 2009';
    $CONFIG['site']['template'] = 'default';
    $CONFIG['registration']['email_confirm']='yes';
    $CONFIG['max_upload_size']=100;
    $CONFIG['use_short_links']='yes';
    $CONFIG['id_max_length']=10;
    $CONFIG['id_use_alpha']='yes';
    $CONFIG['max_download_time']=120;
    $CONFIG['multiupload']=1;
    $CONFIG['allow_unprotected_removal']='no';
    $CONFIG['progress']=$_SESSION['progress_values'][count($_SESSION['progress_values'])-1];
    $CONFIG['logging']['enabled']='yes';
    $CONFIG['logging']['db_level']=4;
    $CONFIG['logging']['syslog_level']=0;
  }
?>
<form method="POST" action="index.php">
<input type="hidden" name="step" value="<?php echo $step; ?>">
<table border="0">
<tr><td>Translation module:</td><td>
<select name="translator">
<option value="">-- Select one --</option>
<?php foreach ($tr as $t) { 
  $selected = $CONFIG['translator']==$t?'selected':'';
  echo '<option value="'.$t.'" '.$selected.'>'.$t.'</option>';
} ?>
</select>
</td></tr>
<tr><td>Default language:</td><td><input type="text" name="defaultlang" value="<?php echo $CONFIG['defaultlang']; ?>"></td></tr>
<tr><td>Authentication module:<br />(LDAP Configuration needs to be done<br /> by hand for now)</td><td>
<select name="auth">
<option value="">-- Select one --</option>
<?php foreach ($auth as $t) { 
  $selected = $CONFIG['auth']==$t?'selected':'';
  echo '<option value="'.$t.'" '.$selected.'>'.$t.'</option>';
} ?>
</select>
</td></tr>
<tr><td>Site title:</td><td><input type="text" name="sitetitle" value="<?php echo $CONFIG['site']['title']; ?>"></td></tr>
<tr><td>WebMaster E-mail:</td><td><input type="text" name="webmaster" value="<?php echo $CONFIG['site']['webmaster']; ?>"></td></tr>
<tr><td>Site E-mail:</td><td><input type="text" name="email" value="<?php echo $CONFIG['site']['email']; ?>"></td></tr>
<tr><td>Confirm registration with e-mail:</td><td><input type="checkbox" name="confirmregistration" value="yes" <?php if ($CONFIG['registration']['email_confirm']=='yes') echo 'checked'; ?> ></td></tr>
<tr><td>Template:</td><td>
<select name="template">
<option value="">-- Select one --</option>
<?php foreach ($templates as $t) { 
  if ($t != '..' and $t != '.' and strpos($t,'.')!==0) {
    $selected = $CONFIG['site']['template']==$t?'selected':'';
    echo '<option value="'.$t.'" '.$selected.'>'.$t.'</option>';
  }
} ?>
</select>
</td></tr>
<tr><td>Template Footer:</td><td><textarea name="sitefooter" cols="50" rows="5"><?php echo $CONFIG['site']['footer']; ?></textarea></td></tr>
<tr><td>Maximum upload size (in MB):</td><td><input type="text" name="max_upload_size" value="<?php echo $CONFIG['max_upload_size']; ?>"></td></tr>
<tr><td>Maximum download time (in Min)<br />0 disables it:</td><td><input type="text" name="max_download_time" value="<?php echo $CONFIG['max_download_time']; ?>"></td></tr>
<tr><td>Max num. of file uploaded per upload:</td><td><input type="text" name="multiupload" value="<?php echo $CONFIG['multiupload']; ?>"></td></tr>
<tr><td>Use Short Links?:</td><td><input type="checkbox" name="use_short_links" value="yes" <?php if ($CONFIG['use_short_links']=='yes') echo 'checked'; ?> ></td></tr>
<tr><td>Length of IDs (suggested min 6):</td><td><input type="text" name="id_max_length" value="<?php echo $CONFIG['id_max_length']; ?>"></td></tr>
<tr><td>Use alphanumeric IDs?:</td><td><input type="checkbox" name="id_use_alpha" value="yes" <?php if ($CONFIG['id_use_alpha']=='yes') echo 'checked'; ?> ></td></tr>
<tr><td>Allow unprotected file removal?:</td><td><input type="checkbox" name="allow_unprotected_removal" value="yes" <?php if ($CONFIG['allow_unprotected_removal']=='yes') echo 'checked'; ?> ></td></tr>
<tr><td>Upload tracking method:</td><td><select name="progress">
<?php
  foreach ($_SESSION['progress_values'] as $v) {
    $sel = $CONFIG['progress']==$v?'selected':'';
    echo '<option value="'.$v.'" '.$sel.'>'.$v.'</option>';
  }
?>
</select></td></tr>
<tr><td>Enable activity logging?:</td><td><input type="checkbox" name="logging" value="yes" <?php if ($CONFIG['logging']['enabled']=='yes') echo 'checked'; ?> ></td></tr>

<tr><td>Database logging level:</td><td><select name="log_db_level">
<?php
  $loglevels = array ( 'Disabled', 'Errors', 'Security', 'Warnings', 'Statistics', 'Info');
  foreach ($loglevels as $k => $l) {
    $sel = $CONFIG['logging']['db_level']==$k?'selected':'';
    echo '<option value="'.$k.'" '.$sel.'>'.$l.'</option>';
  }
?>
</select></td></tr>
<tr><td>Syslog logging level:</td><td><select name="log_syslog_level">
<?php
  foreach ($loglevels as $k => $l) {
    $sel = $CONFIG['logging']['syslog_level']==$k?'selected':'';
    echo '<option value="'.$k.'" '.$sel.'>'.$l.'</option>';
  }
?>
</select></td></tr>
<TR><TD colspan="2"><input type="submit" value="Next &gt;&gt;"></TD></TR>
</table>
</form>
<?php
}

function users($step) {
  global $_POST;
  global $_SESSION;
  global $CONFIG;

  if (isset($_POST['unregistered'])) {
    $error = false;
    $_SESSION['options']['adminuser']=$_POST['adminuser'];
    $_SESSION['options']['adminpassword']=$_POST['adminpassword'];
    $_SESSION['options']['admingroup']=$_POST['admingroup'];
    $_SESSION['options']['registered']=$_POST['registered'];
    $_SESSION['options']['unregistered']=$_POST['unregistered'];
    $CONFIG['register']['nologingroup']=$_POST['unregistered'];
    $CONFIG['register']['default_group']=$_POST['registered'];
    if ($_SESSION['options']['adminuser']=='') {
      $error = true;
      msg('Please provide an administrator name','fail');
    }
    if ($_SESSION['options']['adminpassword']=='') {
      $error = true;
      msg('Please provide an administrator password','fail');
    }
    if ($_SESSION['options']['admingroup']=='') {
      $error = true;
      msg('Please provide an administrators group','fail');
    }
    if ($_SESSION['options']['registered']=='') {
      $error = true;
      msg('Please provide registered users group','fail');
    }
    if ($_SESSION['options']['unregistered']=='') {
      $error = true;
      msg('Please provide an unregistered users default group','fail');
    }
    if (!$error) 
      return $step+1;
  } else if (!isset($_SESSION['options']['adminuser'])) {
    $_SESSION['options']['adminuser']='admin';
    $_SESSION['options']['adminpassword']='';
    $_SESSION['options']['admingroup']='admins';
    $_SESSION['options']['registered']='registered';
    $_SESSION['options']['unregistered']='unregistered';
  }
?>
<form method="POST" action="index.php">
<input type="hidden" name="step" value="<?php echo $step; ?>">
<table border="0">
<tr><td>Administrator:</td><td><input type="text" name="adminuser" value="<?php echo $_SESSION['options']['adminuser']; ?>"></td></tr>
<tr><td>Admin password:</td><td><input type="password" name="adminpassword" value=""></td></tr>
<tr><td>Admin group:</td><td><input type="text" name="admingroup" value="<?php echo $_SESSION['options']['admingroup']; ?>"></td></tr>
<tr><td>Users group:</td><td><input type="text" name="registered" value="<?php echo $_SESSION['options']['registered']; ?>"></td></tr>
<tr><td>Not registered group:</td><td><input type="text" name="unregistered" value="<?php echo $_SESSION['options']['unregistered']; ?>"></td></tr>
<TR><TD colspan="2"><input type="submit" value="Next &gt;&gt;"></TD></TR>
</table>
</form>
<?php
}

function plugins($step) {
  global $_POST;
  global $CONFIG;

  if (isset($_POST['step'])) {
    $CONFIG['plugins']=array();
    foreach ($_POST as $k => $v) {
      if (strpos($k,'plugin_')===0) {
        $CONFIG['plugins'][]=$v;
      }
    }
    return $step+1;
  }

  $plugins = listModules($CONFIG['INSTALL_ROOT'].'/plugins');
?>
<div id="message">Please select which plugins you want to use (better enable all of them):</div>
<form method="POST" action="index.php">
<input type="hidden" name="step" value="<?php echo $step; ?>">
<table border="0">
<?php
foreach ($plugins as $p) {
  echo '<tr><td>'.$p.'</td><td><input type="checkbox" name="plugin_'.$p.'" value="'.$p.'" checked></td></tr>';
}
?>
<TR><TD colspan="2"><input type="submit" value="Next &gt;&gt;"></TD></TR>
</table>
</form>
<?php  
}

function dbconnect($host,$user,$pwd,$db,$debug = false) {

global $dbhandle;
global $CONFIG;

    switch ($CONFIG['database']['type']) {
      case 'mysql':
        if (!($dbhandle = mysql_connect($host,$user,$pwd))) {
          if ($debug) echo '<div id="message">Reason: '.mysql_error().'</div>';
          return false;
        }
        if ($db != '')
          if (!(mysql_select_db($db,$dbhandle))) {
            if ($debug) echo '<div id="message">Reason: '.mysql_error().'</div>';
            return false;
          }
        break;
      case 'pgsql':
        $str = "host=".$host;
        $str .= " port=5432";
        $str .= " dbname=".$db;
        $str .= " user=".$user;
        $str .= " password=".$pwd;
        if (!($dbhandle = pg_connect($str))) {
          if ($debug) echo '<div id="message">Reason: '.pg_last_error().'</div>';
          return false;
        }
        break;
      // LCARD
      case 'mssql':
        $dbhandle = mssql_connect($host, $user, $pwd);
        if (!(mssql_select_db($db, $dbhandle))) {
          if ($debug) echo '<div id="message">Reason: '.mssql_get_last_message().'</div>';
          return false;
        }
        break;
      // LCARD
      default:
        msg('ERROR: dbtype: '.$CONFIG['database']['type'].' not yet supported','fail');
        return false;
        break;
    }
  return true;
}

function dbquery($sql,$params = array(),$debug = false) {
global $dbhandle;
global $CONFIG;

  $query = strtr($sql,$params);
  if ($query == '') return true;

  switch ($CONFIG['database']['type']) {
    case 'mysql':
      if (!mysql_query($query,$dbhandle)) {
        if ($debug) echo '<div id="message">Query failed: '.$query.'<br />Reason: '.mysql_error().'</div>';
          return false;
      }
      break;
    case 'pgsql':
      if (!pg_query($query)) {
        if ($debug) echo '<div id="message">Query failed: '.$query.'<br />Reason: '.pg_last_error().'</div>';
        return false;
      }
      break;
    //LCARD
    case 'mssql':
      // DEBUG ALL SQL SERVER QUERIES
      // print "<br>$query<br>";
      if (! mssql_query($query) ) {
        if ($debug) echo '<div id="message">Query failed: '.$query.'<br />Reason: '.mssql_get_last_message().'</div>';
        return false;
      }
      break;
    //LCARD
  }
  return true;
}

function dbcreatetable($table,$fields,$keys,$debug=false) {
  global $CONFIG;

  switch ($CONFIG['database']['type']) {
    case 'mysql':
      $query = 'CREATE TABLE `'.$table.'` (';
      foreach ($fields as $k => $f) {
        $field = '`'.$k.'`';
        switch ($f['type']) {
          case 'char':
            $field .= ' VARCHAR('.$f['size'].')';
            break;
          case 'int':
            $field .= ' INT('.$f['size'].')';
            break;
          case 'text':
            $field .= ' TEXT';
            break;
          case 'datetime':
            $field .= ' DATETIME';
            break;
          case 'date':
            $field .= ' DATE';
            break;
        }
        $field .= ' '.$f['null'].' '.$f['extra'];
        $query .= $field.','."\n";
      }
      $keylist = '';
      foreach ($keys as $n => $k) {
        $key = '';
        foreach ($k['fields'] as $f) {
          if ($key!='') $key.=',';
          $key .= '`'.$f.'`';
        }
        if ($k['primary']) {
          $key = 'PRIMARY KEY  ('.$key.')';
        } else {
          $key = 'KEY `'.$n.'` ('.$key.')';
          if ($k['unique']) $key = ' UNIQUE '.$key;
        }
        if ($keylist!='') { $keylist .= ','; }
        $keylist .= $key;
      }
      $query .= $keylist;
      $query .= ')';
      return dbquery($query,array(),$debug);
      break;
    
      
      
    case 'pgsql':
      $fieldlist = '';
      foreach ($fields as $k => $f) {
        $field = $k;
        switch ($f['type']) {
          case 'char':
            $field .= ' character varying('.$f['size'].')';
            break;
          case 'int':
            $field .= ' INTEGER';
            break;
          case 'text':
            $field .= ' TEXT';
            break;
          case 'datetime':
            $field .= ' timestamp without time zone';
            break;
          case 'date':
            $field .= ' DATE';
            break;
        }
        $field .= ' '.$f['null'];
        if ($fieldlist != '') 
          $fieldlist .= ','."\n";
        $fieldlist .= $field;
      }
      $query = 'CREATE TABLE '.$table.' ('.$fieldlist.')';
      $res = dbquery($query,array(),$debug);
      if ($res) { /* add the keys */
        foreach ($keys as $n => $k) {
          $key = '';
          foreach ($k['fields'] as $f) {
            if ($key!='') $key.=',';
            $key .= $f;
          }
          if ($k['primary']) {
            $query = 'ALTER TABLE ONLY '.$table.' ADD CONSTRAINT '.$table.'_pkey PRIMARY KEY  ('.$key.')';
          } else {
            $query = 'CREATE '.($k['unique']?'UNIQUE ':'').'INDEX '.$table.'_'.$n.'_idx ON '.$table.' USING btree ('.$key.')';
          }
          $res = dbquery($query,array(),$debug);
        }
      }
      if ($res) {
        foreach ($fields as $k => $f) {
          if ($f['extra']=='auto_increment') {
            $seq = $table.'_'.$k.'_seq';
            $query = 'CREATE SEQUENCE '.$seq.' INCREMENT BY 1 NO MAXVALUE NO MINVALUE CACHE 1';
            $res = dbquery($query,array(),$debug);
            $query = 'ALTER SEQUENCE '.$seq.' OWNED BY '.$table.'.'.$k;
            $res = dbquery($query,array(),$debug);
            if ($res) {
              $query = 'ALTER TABLE '.$table.' ALTER COLUMN '.$k.' SET DEFAULT nextval(\''.$seq.'\'::regclass)';
              $res = dbquery($query,array(),$debug);
            }
          }
        }
      }
      return $res;
      break;
      
     
    //LCARD -Attention to IDENTITY fields
    case 'mssql':
      $fieldlist = '';
      foreach ($fields as $k => $f) {
        $field = '['.$k.']';
        switch ($f['type']) {
          case 'char':
            $field .= ' VARCHAR('.$f['size'].')';
            break;
          case 'int':
            $field .= ' INT';
            break;
          case 'text':
            $field .= ' TEXT';
            break;
          case 'datetime':
            $field .= ' DATETIME';
            break;
          case 'date':
            $field .= ' DATE';
            break;
        }
        $field .= ' '.$f['null'].' '.$f['extra'];
        // AUTO_INCREMENT -> IDENTITY
        $field = str_replace("auto_increment", "IDENTITY", $field);
        $fieldlist .= $field.','."\n";
      }
      $query = 'CREATE TABLE ['.$table.'] ('.$fieldlist.')';
      $res = dbquery($query,array(),$debug);
      if ($res) { /* add the keys */
        foreach ($keys as $n => $k) {
          $key = '';
          foreach ($k['fields'] as $f) {
            if ($key!='') $key.=',';
            $key .= $f;
          }
          if ($k['primary']) {
            $query = 'ALTER TABLE ['.$table.'] ADD CONSTRAINT '.$table.'_pkey PRIMARY KEY  ('.$key.')';
          } else {
            $query = 'CREATE '.($k['unique']?'UNIQUE ':'').'INDEX '.$table.'_'.$n.'_idx ON ['.$table.'] ('.$key.')';
          }
          $res = dbquery($query,array(),$debug);
        }
      }
      return $res;
      break;
     
  }
  return false;
}


function dbinsert($table,$data,$params,$debug) {
  global $CONFIG;
  global $DB_STRUCTURE;

  switch ($CONFIG['database']['type']) {
    case 'mysql':
      $fields = '';
      $values = '';
      foreach ($data as $f => $v) {
        if ($fields != '') $fields .= ',';
        if ($values != '') $values .= ',';
        $fields .= '`'.$f.'`';
        $values .= '"'.mysql_real_escape_string($v).'"';
      }
      $query = 'INSERT INTO `%0'.$table.'` ('.$fields.') VALUES ('.$values.')';
      break;
    case 'pgsql':
      $fields = '';
      $values = '';
      foreach ($data as $f => $v) {
        if ($fields != '') $fields .= ',';
        if ($values != '') $values .= ',';
        $fields .= $f;
        if ($DB_STRUCTURE[$table]['fields'][$f]['type']=='int') 
          $values .= $v;
        else if ($DB_STRUCTURE[$table]['fields'][$f]['type']=='datetime') 
          $values .= 'now()';
        else
          $values .= '\''.pg_escape_string($v).'\'';
      }
      $query = 'INSERT INTO %0'.$table.' ('.$fields.') VALUES ('.$values.')';
      break;
      
    //LCARD
   
    case 'mssql':
      $fields = '';
      $values = '';
      foreach ($data as $f => $v) {
        if ($fields != '') $fields .= ',';
        if ($values != '') $values .= ',';
        
        // Don't put IDENTITY columns into the command
        if ($DB_STRUCTURE[$table]['fields'][$f]['extra'] != 'auto_increment') {
	        $fields .= $f;
	        if ($DB_STRUCTURE[$table]['fields'][$f]['type']=='int') 
	          $values .= $v;
	        else if ($DB_STRUCTURE[$table]['fields'][$f]['type']=='datetime') 
	          $values .= 'getdate()';
	        else
	          $values .= '\''.mssql_escape_string($v).'\'';
        }
      }
    
      $query = 'INSERT INTO %0'.$table.' ('.$fields.') VALUES ('.$values.')';
     
      break;
      //LCARD
  }

  return dbquery($query,$params,$debug);
  
}


function createdb($step) {
  global $CONFIG;
  global $_SESSION;
  global $_POST;
  global $MYSQL_QUERY;
  global $PGSQL_QUERY;
  global $MSSQL_QUERY;
  global $DB_STRUCTURE;
  global $DB_DATA;

  if (isset($_SESSION['substep']) and !(isset($_POST['restart']))) {
    $substep = $_SESSION['substep'];
  } else {
    if ($_SESSION['options']['newdb']==1) {
      $substep=1;
  } else if ($_SESSION['options']['populate']>0) {
      $substep = 2;
    } else {
      $substep = 5;
    }
  }
  $_SESSION['substep']=$substep;
  switch ($CONFIG['database']['type']) {
    case 'mysql':
      $query = $MYSQL_QUERY;
      $dbn = 'mysql';
      break;
    case 'pgsql':
      $query = $PGSQL_QUERY;
      $dbn = 'postgres';
      break;
    // LCARD
    case 'mssql':
      $query = $MSSQL_QUERY;
      $dbn = 'mssql';
      break;
    //LCARD
  }
  $error = false;
  $debug = (isset($_POST['debug']))?true:false;
  if (isset($_POST['proceed']) or isset($_POST['retry']) or isset($_POST['next'])) {
    /* connect to the db */
    if (isset($_POST['proceed']) or isset($_POST['retry'])) 
     $execute = true;
    switch ($substep) {
      case 1: /* database and user creation */
        if ($execute) {
          $error = false;
          if ($_SESSION['options']['newdb']==1) {
            if (dbconnect($CONFIG['database']['host'],$_SESSION['options']['rootuser'],$_SESSION['options']['rootpassword'],$dbn,$debug)) {
              if ($_SESSION['options']['newuser']==1) {
                $params['%1']=$CONFIG['database']['user'];
                $params['%2']=$CONFIG['database']['name'];
                $params['%3']=$CONFIG['database']['password'];
                dbquery($query['dropuser'],$params,$debug);
                if (dbquery($query['createuser'],$params,$debug)) {
                  msg('User creation: SUCCESS','ok');
                } else {
                  $error = true;
                  msg('User creation: FAILED!!!','fail');
                }
              }
              $params['%1']=$CONFIG['database']['name'];
              dbquery($query['dropdb'],$params,false);
              if (dbquery($query['createdb'],$params,$debug)) {
                msg('Database creation: SUCCESS','ok');
              } else {
                $error = true;
                msg('Database creation: FAILED!!!','fail');
              }
              $params['%1']=$CONFIG['database']['user'];
              $params['%2']=$CONFIG['database']['name'];
              $params['%3']=$CONFIG['database']['password'];
              if (dbquery($query['grant'],$params,$debug)) {
                msg('Grant privileges: SUCCESS','ok');
              } else {
                $error = true;
                msg('Grant privileges: FAILED!!!','fail');
              }
            } else {
              $error = true;
              msg('Database connection failed, please review the connection information!','fail');
            } 
          }
        }
        if (!$error) {
          if ($_SESSION['options']['populate']>0)
            $substep++;
          else
            $substep=5;
        }
        break;
      case 2: /* structure load */
        $error = false;
        if ($execute) {
          if (dbconnect($CONFIG['database']['host'],$CONFIG['database']['user'],$CONFIG['database']['password'],$CONFIG['database']['name'],$debug)) {
            $params['%1']=$CONFIG['database']['prefix'];
            foreach ($DB_STRUCTURE as $t => $q) {
              $params['%2']=$t;
              
              if (!dbquery($query['droptable'],$params,$debug))
                $error = true;
             
              if (!dbcreatetable($CONFIG['database']['prefix'].$t,$q['fields'],$q['keys'],$debug)) {
                msg('Table creation FAILED: '.$t,'fail');
                $error = true;
              } else {
                msg('Table creation SUCCESS: '.$t,'ok');
              }
            }
          } else {
            msg('Database connection failed!','fail');
            $error = true;
          }
        }
        if (!$error) {
            msg('Structure load: SUCCESS','ok');
            if ($_SESSION['options']['populate']>1)
              $substep++;
          else
            $substep=5;
        } else {
          msg('Structure load: FAILED','fail');
        }
        break;
      case 3: /* data load */
        $error = false;
        if ($execute) {
          if (dbconnect($CONFIG['database']['host'],$CONFIG['database']['user'],$CONFIG['database']['password'],$CONFIG['database']['name'],$debug)) {
            $params['%0']=$CONFIG['database']['prefix'];
            $params['%1']=$_SESSION['options']['adminuser'];
            $params['%2']=crypt($_SESSION['options']['adminpassword']);
            $params['%3']=$_SESSION['options']['admingroup'];
            $params['%4']=$_SESSION['options']['registered'];
            $params['%5']=$_SESSION['options']['unregistered'];
            $params['%6']=$CONFIG['site']['webmaster'];
            foreach ($DB_DATA['base'] as $t => $q) {
              foreach ($q as $x)  {
                if (!dbinsert($t,$x,$params,$debug)) {
                  msg('Query failed... n. '.$t,'fail');
                  $error = true;
                } else {
                  msg('Data insert SUCCESS: '.$t,'ok');
                }
              }
            }
          } else {
            msg('Database connection failed!','fail');
            $error = true;
          }
        }
        if (!$error) {
          if ($_SESSION['options']['populate']>2)
            $substep++;
          else
            $substep=5;
          msg('<b>Base system load: SUCCESS</b>','ok');
        } else {
          msg('Base system load: FAILED','fail');
        }
        break;
      case 4: /* mode load */
        $mode = $_SESSION['options']['populate']-2;
        $error = false;
        if ($execute) {
          if (dbconnect($CONFIG['database']['host'],$CONFIG['database']['user'],$CONFIG['database']['password'],$CONFIG['database']['name'],$debug)) {
            $params['%0']=$CONFIG['database']['prefix'];
            $params['%1']=$_SESSION['options']['adminuser'];
            $params['%2']=crypt($_SESSION['options']['adminpassword']);
            $params['%3']=$_SESSION['options']['admingroup'];
            $params['%4']=$_SESSION['options']['registered'];
            $params['%5']=$_SESSION['options']['unregistered'];
            $params['%6']=$CONFIG['site']['webmaster'];
            foreach ($DB_DATA['mode_'.$mode] as $t => $q) {
              foreach ($q as $x)  {
                if (!dbinsert($t,$x,$params,$debug)) {
                  msg('Query failed... n. '.$t,'fail');
                  $error = true;
                } else {
                  msg('Data insert SUCCESS: '.$t,'ok');
                }
              }
            }
          } else {
            msg('Database connection failed!','fail');
            $error = true;
          }
        }
        if (!$error) {
          $substep=5;
          msg('<b>Mode data load: SUCCESS</b>','ok');
        } else {
          msg('<b>Mode data load: FAILED</b>','fail');
        }
        break;
      case 5: /* finished */
        return $step+1;
        break;
    }
    echo '<hr>';
  }
  $_SESSION['substep']=$substep;
  $_SESSION['debug']=$debug;
  switch ($substep) {
    case 1: /* database and user creation */
      if ($_SESSION['options']['newuser']==1) {
        $msg = 'now we will proceed to the database and user creation.';
      } else {
        $msg = 'now we will proceed to the database creation.';
      }
      break;
    case 2: /* structure load */
      $msg = 'Let\'s proceed with structure creation.';
      break;
    case 3: /* data load */
      $msg = 'Let\'s load the base system data.';
      break;
    case 4: /* mode load */
      $msg = 'Let\'s load the selected mode data.';
      break;
    case 5: /* finished */
      $msg = 'Database initialization finished';
      break;
  }
?>
<div id="message"><?php echo $msg; ?></div>
<form method="POST" action="index.php">
<input type="hidden" name="step" value="<?php echo $step; ?>">
<input type="checkbox" name="debug" value="debug" <?php if ($_SESSION['debug']) echo 'checked'; ?>> Debug database query errors<br />&nbsp;<br />
<input type="submit" name="restart" value="Restart">
<?php if ($error) { ?><input type="submit" name="retry" value="Retry">
<input type="submit" name="next" value="Skip to Next step &gt;&gt;"><?php } else if ($substep<5) { ?>
<input type="submit" name="proceed" value="Execute">
<?php } else { ?>
<input type="submit" name="proceed" value="Next &gt;&gt;">
<?php } ?>
</form>
<?php
}

function generateConfig() {
  global $CONFIG;

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

function save($step) {
  global $_POST;
  global $CONFIG;
  global $_SESSION;

  if (isset($_POST['download'])) {
    $result = generateConfig();
    ob_clean();
    header('Content-Type: text/plain');
    header('Content-Length: '.strlen($result));
    header('Content-Disposition: attachment; filename="config.inc.php"');
    echo $result;
    exit;
  } else if (isset($_POST['save'])) {
    $result = generateConfig();
    $file = 'config.inc.php';
    if (defined('__NOT_MAIN_SCRIPT')) {
      $file = 'www/'.$file;
    }
    if (file_put_contents($file,$result)) {
      msg('Configuration sucessfully saved!','ok');
      echo '<a href="index.php">click here to start using your new site</a><br /><br />';
    } else {
      msg('Configuration file could not be saved, please proceed with the download!','fail');
    }
  }
?>
<div id="message">Congratulations your setup is almost complete.</div>
<div id="message">Now the remaining step is to save the config.inc.php file to your server.</div>
<div id="message">You can try saving it automatically, and if it's ok you'll be redirected to you new installed site.<br />
Please note that this requires write access to the "www" folder.</div>
<div id="message">Or you can download the file to review and upload to the server.</div>
<form method="POST" action="index.php">
<input type="hidden" name="step" value="<?php echo $step; ?>">
<input type="submit" name="download" value="Download Configuration">
<input type="submit" name="save" value="Save Configuration">
</form>
<?php
}

/*********************** SETUP AND DISPLAY OF PAGE **********************/

if (isset($_SESSION['steps'])) {
  foreach ($_SESSION['steps'] as $k => $l) {
    $steps[$k]['done']=$l;
  }
}

if (isset($steps[$step])) {
  $title = $steps[$step]['title'];
  $fun = $steps[$step]['function'];
}

?>
<html>
<head><TITLE>OpenUpload Setup Script</TITLE>
<style>
body {
  font-family: Helvetica, Arial;
  font-size: 10pt;
}
#header {
}
#logo {
  float:left;
}
#userinfo {
  clear: right;
  float: right;
  height: 40px;
  vertical-align: bottom;
  margin-top: 40px;
  margin-right: 20px;
}
#title {
  background-color: #3161cf;
  color: #ffffff;
  font-size: 12pt;
  font-weight: bold;
  clear: right;
  padding-left: 160px;
  padding-top: 3px;
  padding-bottom:3px;
  text-align: left;
  margin-top: 50px;
}
#left {
  width: 200px;
  float: left;
  clear: both;
}
#left ul {
  list-style:none;
  margin-top: 10px;
  padding: 0;
}
#left li {
 padding-right: 8px;
 padding-left: 8px;
}
#content {
  margin-left: 220px;
  padding-top: 20px;
}
#message {
  padding-bottom: 10px;
}
a {
  color: #3161cf;
  font-weight: bold;
  font-size: 11pt;
  text-decoration: none;
}
a:visited {
  color: #3161cf;
  font-weight: bold;
  font-size: 11pt;
  text-decoration: none;
}
a:hover {
  color: #4c8dff;
  font-weight: bold;
  font-size: 11pt;
  text-decoration: none;
}
#footer {
  clear: both;
  position: fixed;
  bottom: 0px;
  height: 20px;
  width: 100%;
  font-weight: bold;
  font-size: 9pt;
  border-top: 1px solid #000000;
  text-align: center;
  background-color: #ffffff;
}
#footer a {
  color: #3161cf;
  font-weight: bold;
  font-size: 9pt;
  text-decoration: none;
}
#footer a:visited {
  color: #3161cf;
  font-weight: bold;
  font-size: 9pt;
  text-decoration: none;
}
</style>
</head>
<body>
<div>
<div id="header">
<div id="logo"><img src="<?php echo $path; ?>/img/openupload.jpg" border="0"></div>
</div>
<div id="userinfo">
</div>
<div id="title"><?php echo $title; ?></div>
<div id="left">
<ul>
<?php
foreach ($steps as $k => $s) {
  if ($step == $k) {
    $img = 'current.png';
  } else if ($s['done']) {
    $img = 'ok.png';
  } else {
    $img = 'step.png';
  }
  if ($s['done']==false) {
    echo '<li><img border="0" src="'.$path.'/img/setup/'.$img.'"> '.$s['title'].'</li>'."\n";
  } else {
    echo '<li><a href="index.php?step='.$k.'"><img border="0" src="'.$path.'/img/setup/'.$img.'"> '.$s['title'].'</a></li>'."\n";
  }
}
?>
</ul>
</div>
<div id="content">
<?php
  $res = $fun($step); 
  $_SESSION['config']=$CONFIG;
  if ($res != '' and $res != $step) {
    $steps[$step]['done']=true;
    foreach ($steps as $k => $s) {
      $_SESSION['steps'][$k]=$s['done'];
    }
    ob_clean();
    header('location: index.php?step='.$res);
    exit;
  }
?>
</div>
<br />&nbsp;<br />
<!-- footer -->
<div id="footer"><a href="http://openupload.sf.net">Open Upload</a> - Created by Alessandro Briosi &copy; 2009</div>
</div>
</body>
</html>
