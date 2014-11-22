<?php

/* No translation at all */

class NullTranslator extends translatorBase {

  function NullTranslator() {
  }

  function init() {
    /* setup page encoding */ 
    header('Content-Type: text/html; charset=utf8');
    app()->tpl->assign('charset','utf8');

  }
  function translate($txt,$domain = 'openupload') {
      return $txt;
  }
}
?>
