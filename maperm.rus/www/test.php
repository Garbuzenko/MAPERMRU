<?
header('Content-Type: text/html; charset=utf-8');
require_once $_SERVER['DOCUMENT_ROOT'].'/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/lib/functions.php';


//list($csv,$date) = get_csv('budget');
//echo $csv.'<br />'.$date;

$r = get_csv('budget',true);
print_r($r);