<?php
header('Content-type: text/html; charset=utf-8');

setlocale(LC_ALL, 'ru_RU.UTF-8'); 
error_reporting(E_ALL);
ini_set('display_errors', 1);
 
include_once($_SERVER['DOCUMENT_ROOT']."/text_generator/classes/tg.class.php");
$tg = new tg();
 
$tg->run( 
	$text = 'test.txt', 
	$url = array(), 
	$use_vars = array( 'name' => 'Test' ) 
);