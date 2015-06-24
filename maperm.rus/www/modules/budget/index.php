<?
if ($isIndex!=true) exit(header('Location: /'));

list($csv,$date) = get_csv($module);

if (file_exists($_SERVER['DOCUMENT_ROOT'].'/modules/'.$module.'/config.php'))
require_once './modules/'.$module.'/config.php';

require_once './modules/'.$module.'/tmp.inc.php';
?>