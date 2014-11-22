#!/usr/bin/env php
<?php

$header = 'msgid ""
msgstr ""
"Project-Id-Version: OpenUpload\n"
"Report-Msgid-Bugs-To: \n"
"Last-Translator: \n"
"Language-Team: \n"
"MIME-Version: 1.0\n"
"Content-Type: text/plain; charset=UTF-8\n"
"Content-Transfer-Encoding: 8bit\n"';


if ($_SERVER['argc'] != 3) {
  echo "Usage: php2po.php <phpfile> <pofile>\n";
  exit (-1);
}

$src = $_SERVER['argv'][1];
$out = $_SERVER['argv'][2];

require_once($src);

$outlines = '';

foreach ($tr as $k => $v) {
  $outlines .= 'msgid "'.str_replace('"','\"',$k).'"'."\n";
  $outlines .= 'msgstr "'.str_replace('"','\"',$v).'"'."\n\n";
}

$outlines = $header."\n\n".$outlines;

file_put_contents($out,$outlines);

?>


