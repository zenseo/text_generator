<?php
header('Content-type: text/html; charset=utf-8');

$debug = 0;
if($debug > 0) {
	ini_set('error_reporting', E_ALL);
	ini_set ('display_errors', 1);
}

include_once($_SERVER['DOCUMENT_ROOT']."/text_generator/index.php");