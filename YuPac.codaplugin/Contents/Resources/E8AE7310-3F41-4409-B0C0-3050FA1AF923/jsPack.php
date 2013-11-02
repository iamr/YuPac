#!/usr/bin/php
<?php

/* functions */
function growl($title, $notification)
{
	$bundle = escapeshellarg($_ENV["CODA_BUNDLE_PATH"] . '/Support Files/growl');
	exec("osascript $bundle \"$title\" \"$notification\"");
}

/* setup variables that we'll need */
$filepath   = pathinfo((isset($_ENV['CODA_FILEPATH']) && $_ENV['CODA_FILEPATH'] != "" ? $_ENV['CODA_FILEPATH'] : $_ENV['CODA_SITE_LOCAL_PATH']), PATHINFO_DIRNAME);
$lineEnding = $_ENV['CODA_LINE_ENDING'];
$name       = "compressed";
$ext        = "js";
$YUI        = escapeshellarg($_ENV['CODA_BUNDLE_PATH'] . '/Support Files/yuicompressor-2.4.4/build/yuicompressor-2.4.4.jar');

/* get input from Coda */
$fp = fopen('php://stdin', 'r');
$input = "";
while ( $line = fgets($fp, 1024) )
{
	$input .= $line;
}

fclose($fp);

/* 1: remove any commented code */
$input = preg_replace('/<!--.*?-->/s',"", $input);
$input = preg_replace('/\/\*.*?\*\//s',"",$input);
$input = preg_replace('/\/\/.*/',"", $input);

/* 2: snag the src attribute */
preg_match_all('/<script.*?src=\"(.*?)\".*?>/', $input, $jsFiles);
$jsFileNames = $jsFiles[1];
preg_match_all('/<script.*?src=\'(.*?)\'.*?>/', $input, $jsFiles);
$jsFileNames = array_merge($jsFileNames, $jsFiles[1]);

$output = "";
/* 4: run each file through yui */
$goodFiles = array();
$badFiles = array();

growl("YuPac", "Compiling ". count($jsFileNames) . " files...");
$compressedSeparator = "";
foreach($jsFileNames as $url)
{
	if(preg_match('/http|www/', $url) == 1)
	{
		$badFiles[] = $url;
		continue;
	}
	
	//clean up filepath (get rid of ./ and ../ stuff); also handily checks if file exists or not
	$currentFile = realpath($filepath ."/". $url);
	if($currentFile == false)
	{
		$badFiles[] = $url . ": File Not Found";
		continue;
	}
	else
	{
		$currentFile = escapeshellarg($currentFile);
	}
	
	$result = array();
	$return_var = 0;
	exec('java -jar ' . $YUI . ' --preserve-semi ' . $currentFile, $result, $return_var);
	if($return_var == 0)
	{
		$output .= $compressedSeparator . $result[0];
		$goodFiles[] = $url;
		$compressedSeparator = $lineEnding;
	}
	else
	{
		$badFiles[] = $url . ": " . $result[0];
	}
}

/* 5: dump the results into a file */

if(count($output) > 0)
{
	file_put_contents($filepath . "/" . $name . "." . $ext, $output);
	echo "$lineEnding<script src=\"$name.$ext\" type=\"text/javascript\"></script>";
}


/* 6: show results */
$success = "";
if(count($badFiles) == 0 && count($goodFiles) > 0)
{
	$success .= "Compilation Success :)";
}
else if(count($badFiles) > 0 && count($goodFiles) > 0)
{
	$success .= "Compiled with Some Errors :/";
}
else if(count($badFiles) > 0 && count($goodFiles) == 0)
{
	$success .= "Compilation Failed :(";
}

if(count($goodFiles) > 0)
{
	$success .= "\nFiles Compressed:";
	foreach($goodFiles as $url)
	{
		$success .= "\n$url";
	}
}

if(count($badFiles) > 0)
{
	$success .= "\nFiles Skipped:";
	foreach($badFiles as $url)
	{
		$success .= "\n$url";
	}
}
growl("Yupac", $success);
?>