<?php
//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
require_once "./config.php";
require_once "./version.php";
require_once "./lib/db.php";
require_once "./lib/functions.php";
header('Content-Type: text/html; charset=windows-1251');

// ----------------------------------------- Ajax запросы ---------------------------------------------------------------
if (isset($_POST['action']) && $_POST['action'] == 'ajax' && !empty($_POST['module'])) {
    $module = clearData($_POST['module'],'get');
    $com = '';
    
    if (!empty($_POST['component'])) 
    $com = 'components/'.clearData($_POST['component'],'get').'/';
    
    if (file_exists(PATH.'/modules/'.$module.'/'.$com.'ajax.php') && filesize(PATH.'/modules/'.$module.'/'.$com.'ajax.php') > 0)
    require_once './modules/'.$module.'/'.$com.'ajax.php';
    
    exit();
}
// ----------------------------------------------------------------------------------------------------------------------

if (empty($_GET['mod'])) $module = 'main';
else $module = clearData($_GET['mod'],'get');

if (!file_exists($path.$module.'/index.php')) $module = 'main';

$stylecss = get_file('css',$css,$update);
$scripts = get_file('js',$js,$update);
$mod_arr = array($module);
$xx = true;

$head = '';

foreach ($mod_arr as $m) {
    $v = $m;
    $$v = '';
    require_once './modules/'.$m.'/status.php';
    if ($status == 1) {
       
       if (file_exists(PATH.'/modules/'.$m.'/functions.php') && filesize(PATH.'/modules/'.$m.'/functions.php') > 0)
       require_once './modules/'.$m.'/functions.php';
    
       ob_start();
       require_once './modules/'.$m.'/index.php';
    
       if ($xx) {$content = ob_get_clean(); $xx = false;}
       else $$v = ob_get_clean();
    
        if (file_exists(PATH.'/modules/'.$m.'/includes/head.php') && filesize(PATH.'/modules/'.$m.'/includes/head.php') > 0) {
        ob_start();
        require_once './modules/'.$m.'/includes/head.php';
        $head .= ob_get_clean();
        }
    }
}
require_once './tmp.inc.php';
?>