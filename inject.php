<?php

define('RUN_OK', true);
require_once 'functions.php';

if($_SERVER['argc'] < 3)
{
  $msg = <<<EOF
  Usage:
    php inject.php <filename.csv> <filename.xml>
  Injects translations (column 2 of csv) to target items of xml.
  Filename collisions are detected and solved.
EOF;
  die($msg);
}

$filename_csv = $_SERVER['argv'][1];
$filename_xml = $_SERVER['argv'][2];

if(!file_exists($filename_csv))
{
  die("\ncsv file $filename_csv not found\n");
}

if(!file_exists($filename_xml))
{
  die("\nxml file $filename_xml not found\n");
}

if(!checkExtension($filename_csv, 'csv'))
{
  die("\nsource file $filename_csv is not an csv file\n");
}

if(!checkExtension($filename_xml, 'xml'))
{
  die("\nsource file $filename_xml is not an xml file\n");
}

try {
  $xml_content = simplexml_load_file($filename_xml);
}
catch(Exception $e)
{
  echo $e->getMessage();
  die("\n An error has occurred while loading xml source");
}




$helper = 'trans-unit';
$data = getCsvAsArray($filename_csv);

if (count($data))
{
  if(count($data) != count($xml_content->file->body->$helper))
  {
    echo "\nCsv count: " . count($data) . "\n";
    echo "\nXml count: " . count($xml_content->file->body->$helper) . "\n";
    die("\nCsv lines and xml elements count mismatch! Check csv content.");
  }

  $i=0;
  foreach($xml_content->file->body->$helper as $item)
  {
    $item->target = $data[$i][1];
    $i++;
  }
}
else
{
  die("\nData not found in " . $filename_csv);
}

file_put_contents(genFilename($filename_xml), $xml_content->asXml());

