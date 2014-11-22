<?php

/* gettext translation module */

class GettextTranslator extends translatorBase {

  function GetTextTranslator() {
  }

  function init() {
    $locale = app()->user->info('lang');
    $lang = app()->langs[$locale];
    putenv("LANG=".$lang['locale']);
    bindtextdomain('openupload',app()->config['INSTALL_ROOT'].'/locale');
    if (is_dir(app()->config['INSTALL_ROOT'].'/templates/'.app()->config['site']['template'].'/locale')) {
      $r = bindtextdomain('template',app()->config['INSTALL_ROOT'].'/templates/'.app()->config['site']['template'].'/locale');
    } else {
      $r = bindtextdomain('template',app()->config['INSTALL_ROOT'].'/templates/default/locale');
    }
    $r = setlocale(LC_ALL,$lang['locale']);
    if ($r != $lang['locale'])
      app()->message('WARNING: locale '.$lang['locale'].' not supported by your system.'); 
    /* setup page encoding */ 
    if (!isset($lang['charset']) or $lang['charset']=='') {
      $lang['charset']='utf8';
    }
    header('Content-Type: text/html; charset='.$lang['charset']);
    app()->tpl->assign('charset',$lang['charset']);
  }


  function translate($txt,$domain = 'openupload') {
    textdomain($domain);
    return gettext($txt);
  }
}
?>