<?php
header('Content-type: text/html; charset=utf-8');

$debug = 1;
if($debug > 0) {
	ini_set('error_reporting', E_ALL);
	ini_set ('display_errors', 1);
}

define("PATH_TEXT_GENERATOR", $_SERVER['DOCUMENT_ROOT']."/text_generator/");
 
include_once(PATH_TEXT_GENERATOR."classes/tg.class.php");
$tg = new tg();
 
echo $tg->run( 
	$text = 'test.txt', 
	$uid = array(), 
	$use_vars = array( 'name' => 'Test' ) 
);