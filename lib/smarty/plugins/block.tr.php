<?php
/**
 * Smarty plugin
 * @package OpenUpload
 * @subpackage plugins
 */


/**
 * Smarty {tr} block plugin
 *
 * Type:     block
 * Name:     tr
 * Purpose:  Translate contained text with a user defined function "translate"
 * @author Alessandro Briosi 
 * @param array
 * @param Smarty
 * @return string
 * @uses translate()
 */

function smarty_block_tr($params, $content = null, &$smarty, &$repeat) {

  if(!$repeat){
    if (isset($content)) {
//      return htmlentities(translate($content,'template',$params));
      return translate($content, 'template',$params);
    } else {
      return '';
    }
  }
}

?>