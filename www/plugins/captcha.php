<?php
ob_start();
/* disable error reporting to the browser */
ini_set('display_errors',0);

/* this generates a chiper and sets the session relative chiper protection */
include ('../config.inc.php');

require_once($CONFIG['INSTALL_ROOT'].'/plugins/securimage/securimage.php');

$img = new securimage();
$img->ttf_file = $CONFIG['INSTALL_ROOT'].'/plugins/securimage/elephant.ttf';
$img->code_length = 6;
$img->image_width = 200;
$img->image_type = SI_IMAGE_JPEG;
/* remove all output before the image */
ob_clean();
$img->show();

?>
