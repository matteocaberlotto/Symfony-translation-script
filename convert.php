<?php

define('RUN_OK', true);
require_once 'functions.php';

if($_SERVER['argc'] < 2)
{
  $msg = <<<EOF
  Usage:
    php convert.php <filename.xml>
  Converts filename.xml to filename.csv
  Filename collisions are detected and solved.
EOF;
  die($msg);
}

$filename = $_SERVER['argv'][1];

if(!file_exists($filename))
{
  die("\nfile not found\n");
}

if(!checkExtension($filename, 'xml'))
{
  die("\nsource file is not an xml file\n");
}

$content = simplexml_load_file($filename);

$helper = 'trans-unit';

$csvContent = array();
foreach($content->file->body->$helper as $item)
{
  $csvContent []= array( (string) $item->source[0] );
}


$newFilename = genFilename($filename, 'csv');
$fh = fopen($newFilename, "w");

putArrayAsCsv($csvContent, $fh);





