<?php

// Executed to build javascript files

$files = array(
			"microAudio.init",
			"jquery-1.3.2",
			"jquery-ui-1.7.2.min",
			"microAudio",
			"microAudio.widget",
			"jquery.jplayer",
			"soundmanager2",
			"microAudio.bootstrap"
			);

// Turn warnings into exceptions so that they can be caught
function jsBuilderHandler($errno, $errstr, $errfile, $errline) {
	throw new Exception($errstr, $errno);
}
set_error_handler('jsBuilderHandler');

// Include JSMin
require_once(dirname(__FILE__)."/jsmin-1.1.1.php");

// Builds js files for your installation.
$options = microAudioOptions::getInstance();
$options->location = get_option('siteurl')."/wp-content/plugins/microaudio/";
$old_key = $options->key;
$options->key = substr(md5(date('U')),4,6);

foreach ($files as $file) {
	
	// Delete old files
	$filename = dirname(__FILE__)."/js/".$file."-".$old_key.".js";
	try {
		unlink($filename);
	} catch (Exception $e) {
		$options->log_error("Unable to unlink $filename.".$e,2);
	}
	
	// Read js template
	$filename = dirname(__FILE__)."/jstemplates/".$file.".js";
	$contents = '';
	try {
		$fhandle = fopen($filename, 'r');
	} catch (Exception $e) {
		$options->log_error("Unable to process fopen for $filename. ".$e);
	}
	try {
		$contents = fread($fhandle, filesize($filename));
		fclose($fhandle);	
	} catch (Exception $e) {
		$options->log_error("Unable to process fread for $filename. ".$e);
	}
	
	// Replace template tags
	$contents = preg_replace_callback("|(\\{\\[\\$((?:[a-z][a-z_]+))\\]\\})|",'ma_replace_tokens',$contents);
	
	// Minify
	if(!$options->debug) $contents = JSMin::minify($contents);
	
	// Write output js
	$filename = dirname(__FILE__)."/js/".$file."-".$options->key.".js";
	try {
		$fhandle = fopen($filename, 'w');
	} catch (Exception $e) {
		$options->log_error("Unable to process fopen for $filename. ".$e);
	}
	try {
		fwrite($fhandle, $contents);
		fclose($fhandle);
	} catch (Exception $s) {
		$options->log_error("Unable to process frwite for $filename.".$e);
	}
}

function ma_replace_tokens($matches) {
	$options = microAudioOptions::getInstance();
	if(is_bool($options->$matches[2]) || $options->$matches[2] === "") {
		return ((bool)$options->$matches[2])?"true":"false";	
	}
	return $options->$matches[2];
}

// Unset custom error handler
restore_error_handler();