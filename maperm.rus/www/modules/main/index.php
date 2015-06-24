<?
if ($isIndex!=true) exit(header('Location: /'));

if (file_exists(PATH.'/modules/'.$module.'/config.php') && filesize(PATH.'/modules/'.$module.'/config.php') > 0)
require_once './modules/'.$module.'/config.php';

require_once './modules/'.$module.'/tmp.inc.php';
?>