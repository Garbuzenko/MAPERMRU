<?
if ($isIndex!=true) exit(header('Location: /'));

if ($handle = opendir(PATH.'/modules')) {
    while (false !== ($file = readdir($handle))) {
       if ($file!='.' && $file!='..')
       $mod[] = $file;
    }
}

closedir($handle);

$col = count($mod);
       if ($col > 0) {
          for($i=0;$i<$col;$i++) {
             $f = PATH.'/modules/'.$mod[$i].'/config.php';
             if (file_exists($f) && filesize($f) > 0) 
                require_once $f;
                if (!empty($o_title)) {
                    $opendata[] = array(
                    'title' => $o_title,
                    'desc' => $o_desc,
                    'source' => $source,
                    'mod' => $mod[$i],
                    'csv' => get_csv($mod[$i],true)
                    );
                    
                    $o_title = '';
                }
            }
       }

if (file_exists(PATH.'/modules/'.$module.'/config.php') && filesize(PATH.'/modules/'.$module.'/config.php') > 0)
require_once './modules/'.$module.'/config.php';

require_once './modules/'.$module.'/tmp.inc.php';
?>