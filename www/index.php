<?php
/**
 * Project:     OpenUpload
 * File:        www/index.php
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
 * @copyright 2008-2009 Alessandro Briosi
 * @author Alessandro Briosi <tsdogs at briosix dot org>
 * @package OpenUpload
 * @version 0.4
 */

define ('__VALID_CALLING_SCRIPT',true);

/* disable notice error reporting */
error_reporting(E_ALL ^ E_NOTICE);

/* check action */
if (isset($_GET['a'])) {
  $action = $_GET['a'];
} else if (isset($_GET['action'])) {
  $action = $_GET['action'];
} else if (isset($_POST['action'])) {
  $action = $_POST['action'];
} else {
  $action = '';
}
if (isset($_GET['s'])) {
  $step = $_GET['s'];
} else if (isset($_GET['step'])) {
  $step = $_GET['step'];
} else if (isset($_POST['step'])) {
  $step = $_POST['step'];
} else {
  $step = '';
}
$configfile = 'config.inc.php';
if (defined('__NOT_MAIN_SCRIPT')) 
  $configfile = 'www/'.$configfile;
if (file_exists($configfile)) {
  require_once($configfile);
  require_once($CONFIG['INSTALL_ROOT'].'/lib/general.inc.php');
  global $application;

  new Application($CONFIG);
  app()->run($action,$step);
} else {
  require_once('setup.inc.php');
}


?>
