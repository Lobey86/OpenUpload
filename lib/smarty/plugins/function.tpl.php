<?php
/**
 * Smarty plugin
 * @package OpenUpload
 * @subpackage plugins
 */


/**
 * Smarty {tpl} function plugin
 *
 * Type:     function
 * Name:     tpl
 * Input:
 *           - file       (required) - string containing template sub file
 * Purpose:  Returns a file from the template folder, if it does not exsist
 *           it returns a file from the default template
 * @author Alessandro Briosi
 * @param array
 * @param Smarty
 * @return string
 * @uses template_file
 */

function smarty_function_tpl($params, &$smarty)
{
  return template_file($params["file"]);

}
?>