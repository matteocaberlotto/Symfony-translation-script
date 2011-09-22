<?php

if(!defined('RUN_OK'))
{
  die("no.");
}

function putArrayAsCsv($array, $fileHandler)
{
  array_walk($array, '__outputCSV', $fileHandler);
}

function __outputCSV(&$vals, $key, $filehandler) {
  fputcsv($filehandler, $vals, ',', '"');
}

function genFilename($filename, $newExtension = false)
{
  $i = 1;
  $path_parts = pathinfo($filename);
  $basename = $path_parts['filename'];
  $extension = $path_parts['extension'];

  $candidate = $filename;

  if($newExtension)
  {
    $extension = $newExtension;
    $candidate = $basename . '.' . $extension;
  }

  while(file_exists($candidate))
  {
    $candidate = $basename . '_' . $i . '.' . $extension;
    $i++;
  }
  return $candidate;
}

function checkExtension($filename, $ext)
{
  $pattern = '/\.' . $ext . '$/';
  return preg_match($pattern, $filename, $m);
}


function getCsvAsArray($filename)
{
  $return = array();
  $fh = fopen($filename, 'r');
  while( ($data=fgetcsv($fh)) !== false )
  {
    array_push($return, $data);
  }
  return $return;
}
