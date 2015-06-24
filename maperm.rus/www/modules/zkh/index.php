<?
/*
    $header_img = 'zkh.jpg';
    $layer = '';

    $a = db_query("SELECT address, found, wear, dates, lng, lat FROM zkh_homes WHERE lng!=''");
    if ($a !== false) {
      foreach($a as $h) {
        if ($h['wear'] == 0) $h['wear'] = 'нет данных';
        
        if ($h['found'] == 0) $h['found'] = '-';
        else $h['found'] = $h['found'].' г.';
        
        $d = explode(';',$h['dates']);
        $date = $d[0];
        $h['address'] = iconv('utf-8','windows-1251',$h['address']);
        $name = "<div class=\"baloon\">ул. ".$h['address']."<br />Построен: ".$h['found']."<br />Степень износа: ".$h['wear']."<br />Ремонт планируется: ".$date." гг.</div>";
        $obj .= "{'style':'default#houseIcon','name':'".$name."','description':'','type':'Placemark','points':{'lng':".$h['lng'].",'lat':".$h['lat']."}},";
      }
    
    $obj = substr($obj,0,-1);
    $layer = "{'name':'new','center':{'lng':56.28552,'lat':58.01741,'zoom':11},'styles':[],'objects':[".$obj."]}";
    }

require_once './modules/'.$module.'/tmp.inc.php';
*/
if ($isIndex!=true) exit(header('Location: /'));

list($csv,$date) = get_csv($module);

if (file_exists(PATH.'/modules/'.$module.'/config.php') && filesize(PATH.'/modules/'.$module.'/config.php') > 0)
require_once './modules/'.$module.'/config.php';

require_once './modules/'.$module.'/tmp.inc.php';