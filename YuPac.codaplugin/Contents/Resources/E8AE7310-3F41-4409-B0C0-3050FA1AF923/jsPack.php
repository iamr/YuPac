#!/usr/bin/php
<?php

function growl($title, $notification)
{
	$bundle = escapeshellarg($_ENV["CODA_BUNDLE_PATH"] . '/Support Files/growl');
	exec("osascript $bundle \"$title\" \"$notification\"");
}



$filepath   = $_ENV['CODA_SITE_LOCAL_PATH'];
$dir        = escapeshellarg($fileinfo['dirname'] . '/');
$name       = "compressed";
$ext        = "js";
$YUI        = escapeshellarg($_ENV['CODA_BUNDLE_PATH'] . '/Support Files/yuicompressor-2.4.4/build/yuicompressor-2.4.4.jar');

$fp = fopen('php://stdin', 'r');

$input = "";
while ( $line = fgets($fp, 1024) )
	$input .= $line;
fclose($fp);
/* 1: remove any commented code */
$input = preg_replace('/<!--.*?-->/s',"", $input);
$input = preg_replace('/\/\*.*?\*\//s',"",$input);
$input = preg_replace('/\/\/.*/',"", $input);

/* 2: snag the src attribute */
preg_match_all('/<script.*?src=(\".*?\").*?>/', $input, $jsFiles);
$jsFileNames = $jsFiles[1];
preg_match_all('/<script.*?src=(\'.*?\').*?>/', $input, $jsFiles);
array_push($jsFileNames, $jsFiles[1]);

/* 3: remove the pesky quotation marks */
$jsFileNames = preg_replace('/[\"\']/', "", $jsFileNames);

$output = "";
/* 4: run each file through yui */
$goodFiles = array();
$badFiles = array();
foreach($jsFileNames as $url)
{
	if(preg_match('/http|www/', $url) == 1)
	{
		continue;
	}
	$result =  shell_exec('java -jar ' . $YUI . ' ' . escapeshellarg($filepath) ."/". $url);
	if($result != null)
	{
		$output .= $result;
		$goodFiles[] = $url;
	}
	else
	{
		$badFiles[] = $url;
	}
}

/* 5: dump the results into a file */

file_put_contents($filepath . "/" . $name . "." . $ext, $output);

echo "\n<script src=\"$name.$ext\" type=\"text/javascript\"></script>";

$success = "Compilation Succeeded!\nFiles Compressed:";
foreach($goodFiles as $url)
{
	$success .= "\n$url";
}
if(count($badFiles) > 0)
{
	$succes .= "\nFiles Skipped:";
	foreach($badFiles as $url)
	{
		$success .= "\n$url";
	}
}
growl("Yupac", $success);




?>