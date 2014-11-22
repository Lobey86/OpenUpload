#!/usr/bin/env php
<?php

$start = "msgid";
$end   = "msgstr";

if ($_SERVER['argc'] != 3) {
  echo "Usage: po2php.php <pofile> <phpfile>\n";
  exit (-1);
}

$src = $_SERVER['argv'][1];
$out = $_SERVER['argv'][2];

$lines = file($src);
$outlines = '<?php'."\n";
$i=0;
while ($i < count($lines)) {
  if (strpos($lines[$i],$start)===0) {
    $msg = substr($lines[$i],strlen($start)+1,strlen($lines[$i])-strlen($start)+1);
    if (chop($msg) != '""') {
      $i++;
      while (strpos($lines[$i],$end)!==0 and $i<count($lines)) {
        $i++;
      }
      if (strpos($lines[$i],$end)===0) {
        $str = substr($lines[$i],strlen($start)+1,strlen($lines[$i])-strlen($start)+1);
        $outlines.='$tr['.trim(chop($msg)).'] = '.trim(chop($str)).";\n";
      }
    }
  }
  $i++;
}
$outlines .= '?>'."\n";

file_put_contents($out,$outlines);
?>
