#!/bin/php
<?php
include('../www/config.inc.php');
include('../lib/classes.inc.php');
include('../lib/modules/db/mysql.inc.php');
include('../lib/modules/db/txt.inc.php');
if (isset($argv[1]))
  $CONFIG['database']['name']=$argv[1];

if (isset($argv[2]))
 $CONFIG['database']['rootdir']=$argv[2];
else
 $CONFIG['database']['rootdir']='./txt';

$mdb = new MysqlDb($CONFIG);
$mdb = new mysqlDb($CONFIG['database']);
$tdb = new txtDB($CONFIG['database']);

$mdb->init();
$tdb->init();

function app() {
  return NULL;
}

function tr($txt) {
  return $txt;
}

foreach ($tdb->tables as $k => $t) {
  $rows = $mdb->read($k,array(),$t['fields']);
  $tdb->writeTxt($CONFIG['database']['rootdir'].'/'.$k.'.txt',$rows,$t['fields']);
}

?>
