<?php

/* simple array translation */

class PhpArrayTranslator extends translatorBase {
var $update = false;
var $TR;

  function PhpArrayTranslator() {
  }

  function init() {
    $locale = app()->user->info('lang'); 
    $lang = app()->langs[$locale];
    $tr = array();
    $this->files['openupload']=app()->config['INSTALL_ROOT'].'/locale/'.$lang['id'].'.inc.php';
    if (file_exists($this->files['openupload'])) {
      require_once($this->files['openupload']);
      $this->TR['openupload']=$tr;
    }
    $tr = array();
    $this->files['template']=app()->config['INSTALL_ROOT'].'/templates/'.app()->config['site']['template'].
                               '/locale/'.$lang['id'].'.inc.php';
    if (file_exists($this->files['template'])) {
      require_once($this->files['template']);
      $this->TR['template']=$tr;
    } else { /* load default translation */
      $this->files['template']=app()->config['INSTALL_ROOT'].'/templates/'.
                               'default/locale/'.$lang['id'].'.inc.php';
      if (file_exists($this->files['template'])) {
        require_once($this->files['template']);
        $this->TR['template']=$tr;
      }
    }
    /* setup page encoding */ 
    if (!isset($lang['charset']) or $lang['charset']=='') {
      $lang['charset']='utf8';
    }
    header('Content-Type: text/html; charset='.$lang['charset']);
    app()->tpl->assign('charset',$lang['charset']);
  }


  function translate($txt,$domain = 'openupload') {

    if (isset($this->TR[$domain][$txt])) {
      return $this->TR[$domain][$txt];
    } else {
      if ($this->update) {
        /* add the translation to the file */
        $f = @fopen($this->files[$domain],'w+');
        if ($f) {
          fwrite($f,'<?php'."\n");
          foreach ($this->TR[$domain] as $k => $v) {
            fwrite($f,'$tr[\''.str_replace("'","\'",$k).'\']=\''.str_replace("'","\'",$v).'\';'."\n");
          }
          fwrite($f,'$tr[\''.str_replace("'","\'",$txt).'\']=\''.str_replace("'","\'",$txt).'\';'."\n");
          fwrite($f,'?>');
          fclose($f);
          $this->TR[$domain][$txt]=$txt;
        }
      }
      return $txt;
    }
  }
}
?>