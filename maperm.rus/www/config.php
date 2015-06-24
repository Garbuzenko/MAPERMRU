<?
define('DOMAIN', 'http://'.$_SERVER['HTTP_HOST']);
define('PATH', $_SERVER['DOCUMENT_ROOT']);
$isIndex = true;
$mtitle = ' - MAPERM.RU';
$cache = true;
$mainemail = '';

// Параметры для подключения к бд
$db_host = 'localhost';
$db_name = 'zkh';
$db_user = 'root';
$db_pass = '';

$path = $_SERVER['DOCUMENT_ROOT'].'/modules/';
$update = true;
?>