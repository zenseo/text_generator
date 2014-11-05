<?php
define("PATH_TEXT_GENERATOR", $_SERVER['DOCUMENT_ROOT']."/text_generator/");
include_once(PATH_TEXT_GENERATOR."model/tg.class.php");
$tg = new tg();

echo $tg->run( 
	$text = 'test.txt', 
	$uid = array(), 
	$use_vars = array( 'name' => 'test' ) 
);

 