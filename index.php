<?php
header('Content-type: text/html; charset=utf-8');
setlocale(LC_ALL, 'ru_RU.UTF-8'); 

error_reporting(E_ALL);
ini_set('display_errors', 1);
 
include_once($_SERVER['DOCUMENT_ROOT']."/text_generator/classes/tg.class.php");
$stg = new tg();
 
echo $stg->run(
	'title.txt',
	$url = '',
	array(
		'name' => 'Test'
	)
);