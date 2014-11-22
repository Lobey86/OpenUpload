<?php
/* Main library containing the general code for the application */

ob_start();
session_start();

if (isset($CONFIG['SMARTY_DIR'])) 
  define('SMARTY_DIR', $CONFIG['SMARTY_DIR']);
else
  define('SMARTY_DIR', $CONFIG['INSTALL_ROOT'].'/lib/smarty/');
require(SMARTY_DIR . 'Smarty.class.php');
require_once($CONFIG['INSTALL_ROOT'].'/lib/classes.inc.php');
require_once($CONFIG['INSTALL_ROOT'].'/lib/user.inc.php');
require_once($CONFIG['INSTALL_ROOT'].'/lib/main.inc.php');

/* check if the selected template needs personalizations code of some sort */
if (file_exists($CONFIG['INSTALL_ROOT'].'/templates/'.$CONFIG['site']['template'].'/init.inc.php')) {
  include ($CONFIG['INSTALL_ROOT'].'/templates/'.$CONFIG['site']['template'].'/init.inc.php'); 
}

/* remove trailing slash from WWW_ROOT */
if (strrpos($CONFIG['WWW_ROOT'],'/')===strlen($CONFIG['WWW_ROOT'])-1)
   $CONFIG['WWW_ROOT']=substr($CONFIG['WWW_ROOT'],0,strlen($CONFIG['WWW_ROOT'])-1);

/* remove trailing slash from WWW_SERVER */
if (strrpos($CONFIG['WWW_SERVER'],'/')===strlen($CONFIG['WWW_SERVER'])-1)
   $CONFIG['WWW_SERVER']=substr($CONFIG['WWW_SERVER'],0,strlen($CONFIG['WWW_SERVER'])-1);

/*************************************************************************************
 * GLOBAL FUNCTIONS                                                                  *
 *************************************************************************************/

function app() {
  global $application;
  
  return $application;
}

function redirect($url = '') {
  global $_SERVER;
  ob_clean();

  if ($url == '') {
    header('location: '.$_SERVER['PHP_SELF']);
  } else  if (strpos($url,'http://')===FALSE and strpos($url,'https://')===FALSE) {
    header('location: '.$_SERVER['PHP_SELF'].$url);
  } else {
    header('location: '.$url);
  }
  app()->db->free();
  exit(0);
}

/* generates a random string of length between min and max 
 * if alpha is true it will generate an alphanumeric string
 */
function randomName($min = 10, $max = 20, $alpha = false) {
  $result = '';
  $rmax = rand($min,$max);
  for ($i = 0; $i<$rmax; $i++) {
    if ($alpha) {
      $x = rand(0,58);
      echo $x;
      if ($x<25) {
        $x = chr(ord('a') + $x);
      } else if ($x < 35 ) {
        $x = $x - 25;
      } else {
        $x = chr(ord('A') + ($x - 35));
      }
      echo '='.$x.'<br>';
      $result .= $x;
    } else {
      $result.= rand(0,9);
    }
  }
  return $result;
}

function translate($txt,$domain,$args) {
  /* now we retrieve the translated message */
  if (is_object(app()->tr)) 
    $txt = app()->tr->translate($txt,$domain);
  /* if there are arguments replace them */
  if (count($args)>0) {
    $trargs = array();
    $i = 1;
    foreach ($args as $a) {
      $trargs['%'.$i]=$a;
      $i++;
    }
    $txt = strtr($txt,$trargs);
  }
  /* return the trasnalted text */
  return $txt;
}
/**
 * @name tr
 * @param $txt 
 * @param ...
 * @description translates a string from code string.
 *
 */
function tr($txt) {
  /* now we retrieve the translated message */
  $args = array();
  if (func_num_args()>1) {
    $args = func_get_args();
    array_shift($args);
  }
  $txt = translate($txt,'openupload',$args);
  return $txt;
}

function template_file($file) {
  global $CONFIG;

  if ($file == '') return '';
  if (strpos($file,'/')===0) { /* remove leading slash */
    $file = substr($file,1,strlen($file)-1);
  }
  /* guess where "public" template files are placed */
  $base = '.';
  if (defined('__NOT_MAIN_SCRIPT')) {
    $base = './www';
  }
  if (file_exists($base.'/templates/'.$CONFIG['site']['template'].'/'.$file))
    return $CONFIG['WWW_ROOT'].'/templates/'.$CONFIG['site']['template'].'/'.$file;
  else
    return $CONFIG['WWW_ROOT'].'/templates/default/'.$file;
}

/**
Validate an email address.
Provide email address (raw input)
Returns true if the email address has the email 
address format and the domain exists.
Note: taken from here: http://www.linuxjournal.com/article/9585

* Bug Update (2008-12-29 by Ryan Lelek):
    -- Support on Windows PHP for the function 'checkdnsrr' 
       used within the 'validEmail' function has been added.
    -- Pending further investigation.
    -- Discovery/Solution thanks to: Korn�l
    -- http://hu.php.net/manual/en/function.checkdnsrr.php#75158 
*/
function validEmail($email){

  // Begin Bug Fix
    if(!function_exists('checkdnsrr')){
      function checkdnsrr($hostName, $recType = ''){
        if(!empty($hostName)){
          if($recType == ''){
            $recType = "MX";
          }
          exec("nslookup -type=$recType ".escapeshellcmd($hostName), $result);
          // check each line to find the one that starts with the host
          // name. If it exists then the function succeeded.
          foreach ($result as $line) {
            if(eregi("^$hostName",$line)) {
              return true;
            }
          }
          // otherwise there was no mail handler for the domain
          return false;
        }
      return false;
      }
    }
  // End Bug Fix

   $isValid = true;
   $atIndex = strrpos($email, "@");
   if (is_bool($atIndex) && !$atIndex)
   {
      $isValid = false;
   }
   else
   {
      $domain = substr($email, $atIndex+1);
      $local = substr($email, 0, $atIndex);
      $localLen = strlen($local);
      $domainLen = strlen($domain);
      if ($localLen < 1 || $localLen > 64)
      {
         // local part length exceeded
         $isValid = false;
      }
      else if ($domainLen < 1 || $domainLen > 255)
      {
         // domain part length exceeded
         $isValid = false;
      }
      else if ($local[0] == '.' || $local[$localLen-1] == '.')
      {
         // local part starts or ends with '.'
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $local))
      {
         // local part has two consecutive dots
         $isValid = false;
      }
      else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
      {
         // character not valid in domain part
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $domain))
      {
         // domain part has two consecutive dots
         $isValid = false;
      }
      else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                 str_replace("\\\\","",$local)))
      {
         // character not valid in local part unless 
         // local part is quoted
         if (!preg_match('/^"(\\\\"|[^"])+"$/',
             str_replace("\\\\","",$local)))
         {
            $isValid = false;
         }
      }
      if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
      {
         // domain not found in DNS
         $isValid = false;
      }
   }
   return $isValid;
}

/* this function generates a mail from a template */
function sendmail($from,$reply,$to,$subject,$template, $attach = array()) {
  $tpl = &app()->tpl;
  $tpl->assign('subject',$subject); 
  $tpl->assign('from',$from);
  $tpl->assign('to',$to);
  /* generate a boundary */
  $bound1 = "==Multipart_Bounday_x".md5(time())."x";
  /* generate a second boundary for the alternative */
  $header = "From: ".$from."\nReply-To: ".$reply."\n";
  $header .= "Mime-Version: 1.0\n";
  if (count($attach)>0) {
    $bound2 = "==Multipart_Bounday_z".md5(time())."z";
    $header .= "Content-Type: multipart/mixed;\n boundary=\"".$bound1."\"";
  } else {
    $bound2 = $bound1;
    $header .= "Content-Type: multipart/alternative;\n boundary=\"".$bound1."\"";
  }
  $tpl->assign('boudary',$bound2);
  $msg = app()->fetch($template);

  /* now add the attachements */
  if (count($attach)>0) {
    foreach ($attach as $a) {
      $msg .="--".$bound1."\n";
      $msg .="Content-Type: ".$a['mime'].";\n name=\"".$a['name']."\"\n";
      $msg .="Content-Disposition: attachment; filename=\"".$a['name']."\"\n";
      $msg .="Content-Transfer-Encoding: base64\n\n";
      $msg .=chunk_split(base64_encode(file_get_contents($a['file'])));
    }
    $msg .="\n--".$bound1."--\n";
    $msg .="\n--".$bound2."--\n";
  } else {
    $msg .="\n--".$bound1."--\n";
  }
  return mail($to,$subject,$msg,$header,'-f "'.$from.'"');
}

/* tries to use all available methods to determine a file mime type */
function get_mime_type($file,$type) {
  global $CONFIG;
  $mime = $type;
  if (function_exists('finfo_open')) {
    if ($CONFIG['mime_magic_file']!='')
      $finfo = finfo_open(FILEINFO_MIME, $CONFIG['mime_magic_file']);
    else
      $finfo = finfo_open(FILEINFO_MIME);
    $mime = finfo_file($finfo,$file);

    if (strpos($mime,';')) {  /* remove the charset */
      $mime = substr($mime,0,strpos($mime,';'));
    }
    if (strpos($mime,' ')) {  /* remove the charset */
      $mime = substr($mime,0,strpos($mime,' '));
    }
    finfo_close($finfo);
  } else if (function_exists('mime_content_type')) {
    $mime = mime_content_type($file);
  } else { /* TODO: try to do it internally ??? */
  }
  return $mime;
}


?>
